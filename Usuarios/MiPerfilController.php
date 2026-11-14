<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Core/Auditoria.php';
require_once dirname(__DIR__) . '/Core/ValidadorContrasenia.php';
require_once dirname(__DIR__) . '/Usuarios/UsuarioRepository.php';
require_once dirname(__DIR__) . '/Usuarios/UsuarioService.php';

/**
 * MiPerfilController — el alumno/profesor edita SUS propios datos.
 *
 * El id del usuario sale siempre de la sesión, nunca del formulario. Rol, DNI y estado
 * no se pueden cambiar desde acá. El email y la contraseña piden la contraseña actual.
 */
class MiPerfilController
{
    private UsuarioRepository $repo;
    private UsuarioService    $service;
    private int               $idUsuario;

    public function __construct()
    {
        AuthGuard::verificarRol(['alumno', 'profesor']);
        $this->repo      = new UsuarioRepository();
        $this->service   = new UsuarioService();
        $this->idUsuario = (int) $_SESSION['id_usuario'];
    }

    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->alerta('warning', 'Ups', 'Solicitud no válida.');
        }

        match ($_POST['accion'] ?? '') {
            'datos'       => $this->cambiarDatos(),
            'email'       => $this->cambiarEmail(),
            'contrasenia' => $this->cambiarContrasenia(),
            default       => $this->alerta('warning', 'Ups', 'Acción desconocida.'),
        };
    }

    private function cambiarDatos(): void
    {
        $nombre   = trim((string) ($_POST['nombre'] ?? ''));
        $apellido = trim((string) ($_POST['apellido'] ?? ''));
        $fecha    = trim((string) ($_POST['fecha_nacimiento'] ?? ''));

        // Letras (con tildes), espacios, apóstrofe, punto y guion; hasta 50 caracteres (límite de la columna).
        $patron = "/^\\p{L}[\\p{L} '’.\\-]{0,49}$/u";
        if (!preg_match($patron, $nombre) || !preg_match($patron, $apellido)) {
            $this->alerta('warning', 'Revisá tus datos', 'El nombre y el apellido solo pueden tener letras, espacios, apóstrofes, puntos y guiones (hasta 50 caracteres).');
        }
        if (!$this->service->validarFechaNacimiento($fecha) || $fecha < '1900-01-01') {
            $this->alerta('warning', 'Revisá tus datos', 'La fecha de nacimiento no es válida.');
        }

        if (!$this->repo->actualizarDatosPersonales($this->idUsuario, $nombre, $apellido, $fecha)) {
            $this->alerta('error', 'Error', 'No se pudieron guardar tus datos. Probá de nuevo.');
        }

        Auditoria::log('Perfil', 'modificar_datos_personales', 'el usuario actualizó su nombre, apellido o fecha de nacimiento');
        $this->alerta('success', '¡Listo!', 'Tus datos se guardaron correctamente.');
    }

    private function cambiarEmail(): void
    {
        $this->exigirContraseniaActual();

        $email = trim((string) ($_POST['email'] ?? ''));
        $errores = ValidadorContrasenia::email($email);
        if ($errores || strlen($email) > 100) {
            $this->alerta('warning', 'Revisá tu email', $errores[0] ?? 'El email es demasiado largo.');
        }
        if ($this->repo->emailExisteExcluyendo($email, $this->idUsuario)) {
            $this->alerta('warning', 'Ese email ya está en uso', 'Probá con otro.');
        }

        if (!$this->repo->actualizarEmail($this->idUsuario, $email)) {
            $this->alerta('error', 'Error', 'No se pudo cambiar el email. Probá de nuevo.');
        }

        $_SESSION['email'] = $email;
        Auditoria::log('Perfil', 'cambiar_email', 'el usuario cambió su email');
        $this->alerta('success', '¡Listo!', 'Tu email se actualizó. Usá el nuevo para iniciar sesión.');
    }

    private function cambiarContrasenia(): void
    {
        $actual = $this->exigirContraseniaActual();

        $nueva     = (string) ($_POST['nueva'] ?? '');
        $confirmar = (string) ($_POST['confirmar'] ?? '');

        $errores = ValidadorContrasenia::password($nueva);
        if ($errores) {
            $this->alerta('warning', 'Revisá la contraseña nueva', $errores[0]);
        }
        if (!$this->service->contrasenasCoinciden($nueva, $confirmar)) {
            $this->alerta('warning', 'Revisá la contraseña nueva', 'Las contraseñas no coinciden.');
        }
        if ($nueva === $actual) {
            $this->alerta('warning', 'Revisá la contraseña nueva', 'La contraseña nueva tiene que ser distinta de la actual.');
        }

        if (!$this->repo->actualizarContrasenia($this->idUsuario, $this->service->hashContrasenia($nueva))) {
            $this->alerta('error', 'Error', 'No se pudo cambiar la contraseña. Probá de nuevo.');
        }

        session_regenerate_id(true);
        Auditoria::log('Perfil', 'cambiar_contrasenia', 'el usuario cambió su contraseña');
        $this->alerta('success', '¡Listo!', 'Tu contraseña se cambió correctamente.');
    }

    /** Corta con una alerta si la contraseña actual no coincide; si coincide, la devuelve. */
    private function exigirContraseniaActual(): string
    {
        $actual = (string) ($_POST['actual'] ?? '');
        $hash   = $this->repo->obtenerHashContrasenia($this->idUsuario);

        if ($actual === '' || $hash === null || !password_verify($actual, $hash)) {
            Auditoria::log('Perfil', 'cambio_rechazado', 'contraseña actual incorrecta');
            $this->alerta('warning', 'Contraseña incorrecta', 'La contraseña actual no es correcta.');
        }
        return $actual;
    }

    private function alerta(string $tipo, string $titulo, string $msg): never
    {
        $_SESSION['alerta'] = compact('tipo', 'titulo', 'msg');
        header('Location: ../views/mi_perfil.php');
        exit;
    }
}
