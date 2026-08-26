<?php
require_once dirname(__DIR__) . '/Core/Database.php';
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Usuarios/UsuarioService.php';
require_once dirname(__DIR__) . '/Core/ValidadorContrasenia.php';




/**
 * RegistroController — registro de nuevos usuarios.
 */
class RegistroController
{
    private UsuarioService $service;

    public function __construct()
    {
        $this->service = new UsuarioService();
    }

    public function handle(): void
    {
        $esPublico = isset($_POST['registro_publico']);

        if ($esPublico) {
            if (session_status() === PHP_SESSION_NONE) session_start();
        } else {
            AuthGuard::verificarRol(['bibliotecario']);
        }






        // Validar campos obligatorios
        $campos = ['nombre', 'apellido', 'fecha_nacimiento', 'dni', 'email', 'contrasenia'];
        if ($esPublico) $campos[] = 'confirmar_contrasenia';

        foreach ($campos as $campo) {
            if (empty($_POST[$campo])) {
                $this->errorRegistro($esPublico, 'Todos los campos son obligatorios.');
            }
        }


        /*FALTA AÑADIR CSS PARA QUE SE MUESTRE EN LA PÁGINA 
        INICIO SESION Y REGISTRO EL MENSAJE*/

        /*ESTA PARTE SE RELACIONA CON ValidadorContrasenia.php */




        // NUEVO: validar formato de email
        $erroresEmail = ValidadorContrasenia::email($_POST['email']);
        if (!empty($erroresEmail)) {
           $this->errorRegistro($esPublico, $erroresEmail[0]);
        }




        // NUEVO: validar formato de contraseña (solo si es registro público,
        // porque el bibliotecario podría estar creando la cuenta con una temporal)
        if ($esPublico) {
            $erroresPassword = ValidadorContrasenia::password($_POST['contrasenia']);
        if (!empty($erroresPassword)) {
            $this->errorRegistro($esPublico, $erroresPassword[0]);
             }
        }





        // Confirmar contraseñas (solo registro público)
        if ($esPublico && !$this->service->contrasenasCoinciden($_POST['contrasenia'], $_POST['confirmar_contrasenia'])) {
            $this->errorRegistro($esPublico, 'Las contraseñas no coinciden.');
        }





        // Validar fecha de nacimiento
        if (!$this->service->validarFechaNacimiento($_POST['fecha_nacimiento'])) {
            $this->errorRegistro($esPublico, 'La fecha de nacimiento no puede ser futura.');
        }

        $fechaDB      = $this->service->formatearFecha($_POST['fecha_nacimiento']);
        $passwordHash = $this->service->hashContrasenia($_POST['contrasenia']);
        $db           = Database::getConexion();

        // Limpiar expirados
        $db->query("DELETE FROM registros_pendientes WHERE expira_en < NOW()");

        // Verificar duplicados
        if ($this->emailExiste($db, $_POST['email'])) {
            $this->errorRegistro($esPublico, 'El email ya está registrado.');
        }
        if ($this->dniExiste($db, $_POST['dni'])) {
            $this->errorRegistro($esPublico, 'El DNI ya está registrado.');
        }

        if ($esPublico) {
            $this->registroConEmail($db, $fechaDB, $passwordHash);
        } else {
            $this->registroDirecto($db, $fechaDB, $passwordHash);
        }
    }

    public function verificar(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_GET['token'])) {
            header('Location: ../views/index.php');
            exit;
        }

        $token = trim($_GET['token']);
        $db    = Database::getConexion();

        $stmt = $db->prepare(
            "SELECT id, nombre, apellido, fecha_nacimiento, dni, email, password_hash
             FROM registros_pendientes
             WHERE token = ? AND expira_en > NOW()"
        );
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $pendiente = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$pendiente) {
            header('Location: ../views/registro.php?error=' . urlencode(
                'El enlace de verificación expiró o ya fue utilizado. Podés registrarte nuevamente.'
            ));
            exit;
        }

        // Caso borde: ya existe (doble clic)
        if ($this->emailExiste($db, $pendiente['email'])) {
            $this->eliminarPendiente($db, $token);
            header('Location: ../views/index.php?exito=' . urlencode('Tu cuenta ya está activa. Iniciá sesión.'));
            exit;
        }

        $stmt = $db->prepare("CALL registrar_alumno(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss',
            $pendiente['nombre'],
            $pendiente['apellido'],
            $pendiente['fecha_nacimiento'],
            $pendiente['dni'],
            $pendiente['email'],
            $pendiente['password_hash']
        );

        if ($stmt->execute()) {
            $stmt->close();
            $this->eliminarPendiente($db, $token);
            header('Location: ../views/index.php?exito=' . urlencode('¡Cuenta verificada con éxito! Ya podés iniciar sesión.'));
        } else {
            error_log("Error verificar_email: " . $stmt->error);
            $stmt->close();
            header('Location: ../views/registro.php?error=' . urlencode('No se pudo completar el registro. Contactá al administrador.'));
        }
        exit;
    }

    private function registroConEmail(mysqli $db, string $fechaDB, string $passwordHash): void
    {
        // Eliminar solicitud pendiente anterior del mismo email/DNI
        $stmt = $db->prepare("DELETE FROM registros_pendientes WHERE email = ? OR dni = ?");
        $stmt->bind_param('ss', $_POST['email'], $_POST['dni']);
        $stmt->execute();
        $stmt->close();

        $token  = bin2hex(random_bytes(32));
        $expira = (new DateTime())->modify('+24 hours')->format('Y-m-d H:i:s');

        $stmt = $db->prepare(
            "INSERT INTO registros_pendientes
                (nombre, apellido, fecha_nacimiento, dni, email, password_hash, token, expira_en)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssssssss',
            $_POST['nombre'], $_POST['apellido'], $fechaDB,
            $_POST['dni'], $_POST['email'], $passwordHash, $token, $expira
        );

        if (!$stmt->execute()) {
            error_log("Error guardar registro pendiente: " . $stmt->error);
            header('Location: ../views/registro.php?error=' . urlencode('No se pudo iniciar el registro. Intentá de nuevo.'));
            exit;
        }
        $stmt->close();

        $enlace = $this->construirEnlaceVerificacion($token);

        try {
            $this->enviarEmailVerificacion($_POST['email'], $_POST['nombre'], $_POST['apellido'], $enlace);
            header('Location: ../views/registro.php?pendiente=' . urlencode(
                '¡Registro iniciado! Te enviamos un email a ' . $_POST['email'] . '. Hacé clic en el enlace para activar tu cuenta.'
            ));
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $this->eliminarPendiente($db, $token);
            error_log("PHPMailer error: " . $e->getMessage());
            header('Location: ../views/registro.php?error=' . urlencode(
                'No se pudo enviar el email de verificación. Verificá que el email sea correcto e intentá de nuevo.'
            ));
        }
        exit;
    }

    private function registroDirecto(mysqli $db, string $fechaDB, string $passwordHash): void
    {
        $stmt = $db->prepare("CALL registrar_alumno(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss',
            $_POST['nombre'], $_POST['apellido'], $fechaDB,
            $_POST['dni'], $_POST['email'], $passwordHash
        );

        if ($stmt->execute()) {
            $_SESSION['alerta'] = ['tipo' => 'success', 'titulo' => '¡Éxito!', 'msg' => 'Usuario registrado correctamente.'];
        } else {
            error_log("Error registrar_alumno (admin): " . $stmt->error);
            $_SESSION['alerta'] = ['tipo' => 'error', 'titulo' => 'Error', 'msg' => 'No se pudo registrar el usuario. Intentá de nuevo.'];
        }
        $stmt->close();
        header('Location: ../views/menu.php');
        exit;
    }

    private function enviarEmailVerificacion(string $email, string $nombre, string $apellido, string $enlace): void
    {
        require_once dirname(__DIR__) . '/views/PHPmailer/PHPMailer.php';
        require_once dirname(__DIR__) . '/views/PHPmailer/SMTP.php';
        require_once dirname(__DIR__) . '/views/PHPmailer/Exception.php';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tatomulqui@gmail.com';
        $mail->Password   = 'gqph sxda bwqq luti';
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('tatomulqui@gmail.com', 'ViBlio');
        $mail->addAddress($email, "$nombre $apellido");
        $mail->isHTML(true);
        $mail->Subject = 'Verificá tu cuenta en ViBlio';

        $nombreHtml = htmlspecialchars($nombre);
        $mail->Body = $this->plantillaEmail($nombreHtml, $enlace);
        $mail->AltBody = "Hola $nombre, verificá tu cuenta en ViBlio: $enlace";
        $mail->send();
    }

    private function plantillaEmail(string $nombre, string $enlace): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head><meta charset="UTF-8"></head>
        <body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:40px 0;">
            <tr><td align="center">
              <table width="580" cellpadding="0" cellspacing="0"
                     style="background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,.08);">
                <tr>
                  <td style="background:#10B981;padding:36px 32px;text-align:center;">
                    <h1 style="color:#fff;margin:0;font-size:30px;letter-spacing:2px;">ViBlio</h1>
                    <p style="color:#d1fae5;margin:6px 0 0;font-size:13px;">Sistema de Gestión de Biblioteca</p>
                  </td>
                </tr>
                <tr>
                  <td style="padding:40px 36px;">
                    <h2 style="color:#111827;margin:0 0 12px;font-size:20px;">¡Hola, {$nombre}!</h2>
                    <p style="color:#4b5563;line-height:1.7;margin:0 0 32px;">
                      Gracias por registrarte en <strong>ViBlio</strong>.
                      Para activar tu cuenta hacé clic en el botón de abajo.<br>
                      <small style="color:#6b7280;">Este enlace es válido por <strong>24 horas</strong>.</small>
                    </p>
                    <table width="100%" cellpadding="0" cellspacing="0"><tr><td align="center">
                      <a href="{$enlace}" style="display:inline-block;background:#10B981;color:#fff;
                         text-decoration:none;padding:14px 40px;border-radius:7px;font-size:16px;font-weight:bold;">
                        ✓ Verificar mi cuenta
                      </a>
                    </td></tr></table>
                    <p style="color:#9ca3af;font-size:11px;margin:28px 0 0;word-break:break-all;">
                      ¿No funciona el botón? Copiá este enlace:<br>
                      <a href="{$enlace}" style="color:#10B981;">{$enlace}</a>
                    </p>
                  </td>
                </tr>
                <tr>
                  <td style="background:#f9fafb;padding:18px 36px;text-align:center;border-top:1px solid #e5e7eb;">
                    <p style="color:#9ca3af;font-size:11px;margin:0;">
                      © 2025 ViBlio &nbsp;·&nbsp; Mensaje automático, no respondas.
                    </p>
                  </td>
                </tr>
              </table>
            </td></tr>
          </table>
        </body>
        </html>
        HTML;
    }

    private function construirEnlaceVerificacion(string $token): string
    {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host  = $_SERVER['HTTP_HOST'];
        $base  = dirname(dirname($_SERVER['REQUEST_URI']));
        return $proto . $host . rtrim($base, '/') . "/controlers/verificar_email.php?token=$token";
    }

    private function emailExiste(mysqli $db, string $email): bool
    {
        $stmt = $db->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    private function dniExiste(mysqli $db, string $dni): bool
    {
        $stmt = $db->prepare("SELECT id_persona FROM persona WHERE dni = ?");
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    private function eliminarPendiente(mysqli $db, string $token): void
    {
        $stmt = $db->prepare("DELETE FROM registros_pendientes WHERE token = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->close();
    }

    private function errorRegistro(bool $esPublico, string $mensaje): never
    {
        if ($esPublico) {
            header('Location: ../views/registro.php?error=' . urlencode($mensaje));
        } else {
            $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => $mensaje];
            header('Location: ../views/menu.php');
        }
        exit;
    }
}
