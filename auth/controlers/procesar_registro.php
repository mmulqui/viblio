<?php
if (isset($_POST["registro_publico"])) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../models/conexion.php");
} else {
    require_once("../controlers/auth.php");
    verificarRol(["bibliotecario"]);
    require_once("../models/conexion.php");
}

$campos = ["nombre", "apellido", "fecha_nacimiento", "dni", "email", "contrasenia"];
if (isset($_POST["registro_publico"])) {
    $campos[] = "confirmar_contrasenia";
}

foreach ($campos as $campo) {
    if (empty($_POST[$campo])) {
        $msg = urlencode("Todos los campos son obligatorios.");
        if (isset($_POST["registro_publico"])) {
            header("Location: ../views/registro.php?error=$msg");
        } else {
            $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'Todos los campos son obligatorios.'];
            header("Location: ../views/menu.php");
        }
        exit;
    }
}

// Doble check de contraseña (solo registro público)
if (isset($_POST["registro_publico"])) {
    if ($_POST["contrasenia"] !== $_POST["confirmar_contrasenia"]) {
        header("Location: ../views/registro.php?error=" . urlencode("Las contraseñas no coinciden."));
        exit;
    }
}

// Valida fecha de nacimiento
$hoy  = new DateTime();
$fecha = DateTime::createFromFormat("Y-m-d", $_POST["fecha_nacimiento"]);
if (!$fecha || $fecha >= $hoy) {
    $msg = urlencode("La fecha de nacimiento no puede ser futura.");
    if (isset($_POST["registro_publico"])) {
        header("Location: ../views/registro.php?error=$msg");
    } else {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'La fecha de nacimiento no puede ser futura.'];
        header("Location: ../views/menu.php");
    }
    exit;
}
$fechaParaDB = $fecha->format("Y-m-d");

$objeto   = new conexion();
$conexion = $objeto->conectar();

// Limpia registros pendientes expirados
$conexion->query("DELETE FROM registros_pendientes WHERE expira_en < NOW()");

// Verifica email duplicado en la tabla de usuarios
$stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
$stmt->bind_param("s", $_POST["email"]);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $msg = urlencode("El email ya está registrado.");
    if (isset($_POST["registro_publico"])) {
        header("Location: ../views/registro.php?error=$msg");
    } else {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'El email ya está registrado.'];
        header("Location: ../views/menu.php");
    }
    exit;
}
$stmt->close();

// Verifica DNI duplicado en la tabla persona
$stmt = $conexion->prepare("SELECT id_persona FROM persona WHERE dni = ?");
$stmt->bind_param("s", $_POST["dni"]);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $msg = urlencode("El DNI ya está registrado.");
    if (isset($_POST["registro_publico"])) {
        header("Location: ../views/registro.php?error=$msg");
    } else {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'El DNI ya está registrado.'];
        header("Location: ../views/menu.php");
    }
    exit;
}
$stmt->close();

$passwordHash = password_hash($_POST["contrasenia"], PASSWORD_BCRYPT, ["cost" => 12]);


if (isset($_POST["registro_publico"])) {

    // Elimina cualquier solicitud pendiente anterior con el mismo email o DNI
    $stmt = $conexion->prepare("DELETE FROM registros_pendientes WHERE email = ? OR dni = ?");
    $stmt->bind_param("ss", $_POST["email"], $_POST["dni"]);
    $stmt->execute();
    $stmt->close();

    // Genera token seguro de 64 caracteres
    $token  = bin2hex(random_bytes(32));
    $expira = (new DateTime())->modify('+24 hours')->format('Y-m-d H:i:s');

    $stmt = $conexion->prepare(
        "INSERT INTO registros_pendientes
            (nombre, apellido, fecha_nacimiento, dni, email, password_hash, token, expira_en)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "ssssssss",
        $_POST["nombre"],
        $_POST["apellido"],
        $fechaParaDB,
        $_POST["dni"],
        $_POST["email"],
        $passwordHash,
        $token,
        $expira
    );

    if (!$stmt->execute()) {
        error_log("Error al guardar registro pendiente: " . $stmt->error);
        header("Location: ../views/registro.php?error=" . urlencode("No se pudo iniciar el registro. Intentá de nuevo."));
        exit;
    }
    $stmt->close();

    // Construir el enlace de verificación de forma dinámica (funciona en cualquier entorno)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host     = $_SERVER['HTTP_HOST'];
    $base     = dirname(dirname($_SERVER['REQUEST_URI'])); // sube de /controlers a la raíz del proyecto
    $enlace   = $protocol . $host . rtrim($base, '/') . "/controlers/verificar_email.php?token=" . $token;

    require_once("../views/PHPmailer/PHPMailer.php");
    require_once("../views/PHPmailer/SMTP.php");
    require_once("../views/PHPmailer/Exception.php");

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tatomulqui@gmail.com';   
        $mail->Password   = 'gqph sxda bwqq luti';       
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('tu_email@gmail.com', 'ViBlio');
        $mail->addAddress($_POST["email"], $_POST["nombre"] . " " . $_POST["apellido"]);
        $mail->isHTML(true);
        $mail->Subject = 'Verificá tu cuenta en ViBlio';

        $nombre = htmlspecialchars($_POST["nombre"]);

        $mail->Body = <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head><meta charset="UTF-8"></head>
        <body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:40px 0;">
            <tr>
              <td align="center">
                <table width="580" cellpadding="0" cellspacing="0"
                       style="background:#ffffff;border-radius:10px;overflow:hidden;
                              box-shadow:0 4px 12px rgba(0,0,0,.08);">

                  <tr>
                    <td style="background:#10B981;padding:36px 32px;text-align:center;">
                      <h1 style="color:#ffffff;margin:0;font-size:30px;letter-spacing:2px;">ViBlio</h1>
                      <p style="color:#d1fae5;margin:6px 0 0;font-size:13px;">
                        Sistema de Gestión de Biblioteca
                      </p>
                    </td>
                  </tr>

                  <tr>
                    <td style="padding:40px 36px;">
                      <h2 style="color:#111827;margin:0 0 12px;font-size:20px;">
                        ¡Hola, {$nombre}!
                      </h2>
                      <p style="color:#4b5563;line-height:1.7;margin:0 0 12px;">
                        Gracias por registrarte en <strong>ViBlio</strong>. Para activar tu
                        cuenta hacé clic en el botón de abajo.
                      </p>
                      <p style="color:#6b7280;font-size:13px;margin:0 0 32px;">
                        Este enlace es válido por <strong>24 horas</strong>.
                        Si no creaste esta cuenta, podés ignorar este correo.
                      </p>

                      <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="center">
                            <a href="{$enlace}"
                               style="display:inline-block;background:#10B981;color:#ffffff;
                                      text-decoration:none;padding:14px 40px;border-radius:7px;
                                      font-size:16px;font-weight:bold;letter-spacing:.5px;">
                              ✓ Verificar mi cuenta
                            </a>
                          </td>
                        </tr>
                      </table>

                      <p style="color:#9ca3af;font-size:11px;margin:28px 0 0;word-break:break-all;">
                        ¿No funciona el botón? Copiá este enlace en tu navegador:<br>
                        <a href="{$enlace}" style="color:#10B981;">{$enlace}</a>
                      </p>
                    </td>
                  </tr>

                  <tr>
                    <td style="background:#f9fafb;padding:18px 36px;text-align:center;
                                border-top:1px solid #e5e7eb;">
                      <p style="color:#9ca3af;font-size:11px;margin:0;">
                        © 2025 ViBlio &nbsp;·&nbsp; Este es un mensaje automático, por favor no respondas.
                      </p>
                    </td>
                  </tr>

                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>
        HTML;

        $mail->AltBody = "Hola {$nombre}, verificá tu cuenta en ViBlio haciendo clic en: {$enlace}";

        $mail->send();

        header("Location: ../views/registro.php?pendiente=" . urlencode(
            "¡Registro iniciado! Te enviamos un email a " . $_POST["email"] . ". Hacé clic en el enlace para activar tu cuenta."
        ));

    } catch (\PHPMailer\PHPMailer\Exception $e) {
        // Si el envío falla, eliminar el registro pendiente para que el usuario pueda reintentar
        $cleanup = $conexion->prepare("DELETE FROM registros_pendientes WHERE token = ?");
        $cleanup->bind_param("s", $token);
        $cleanup->execute();
        $cleanup->close();

        error_log("PHPMailer error en verificación de registro: " . $mail->ErrorInfo);
        header("Location: ../views/registro.php?error=" . urlencode(
            "No se pudo enviar el email de verificación. Verificá que el email sea correcto e intentá de nuevo."
        ));
    }
    exit;
}


$stmt = $conexion->prepare("CALL registrar_alumno(?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "ssssss",
    $_POST["nombre"],
    $_POST["apellido"],
    $fechaParaDB,
    $_POST["dni"],
    $_POST["email"],
    $passwordHash
);

if ($stmt->execute()) {
    $_SESSION['alerta'] = ['tipo' => 'success', 'titulo' => '¡Éxito!', 'msg' => 'Usuario registrado correctamente.'];
    header("Location: ../views/menu.php");
} else {
    error_log("Error al registrar alumno (admin): " . $stmt->error);
    $_SESSION['alerta'] = ['tipo' => 'error', 'titulo' => 'Error', 'msg' => 'No se pudo registrar el usuario. Intentá de nuevo.'];
    header("Location: ../views/menu.php");
}
exit;