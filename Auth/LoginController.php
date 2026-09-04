<?php
require_once dirname(__DIR__) . '/Core/Database.php';
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Core/ValidadorContrasenia.php';
require_once dirname(__DIR__) . '/Core/Auditoria.php'; //Conectado con auditoria.php

/**
 * LoginController — maneja el inicio de sesión.
 */
class LoginController
{
    public function handle(): void
    {
        $this->configurarSesionSegura();

        // Si ya hay sesión, redirigir directamente
        if (!empty($_SESSION['id_usuario'])) {
            $this->redirigirPorRol($_SESSION['rol'] ?? '');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/index.php');
            exit;
        }





        


        /*FALTA AÑADIR CSS PARA QUE SE MUESTRE EN LA PÁGINA 
        INICIO SESION Y REGISTRO EL MENSAJE*/

        
        /*ESTA PARTE SE RELACIONA CON ValidadorContrasenia.php */

        $email       = trim($_POST['email'] ?? '');
        $contrasenia = $_POST['contrasenia'] ?? '';

        $errores = array_merge(
            ValidadorContrasenia::email($email),
            ValidadorContrasenia::requerido($contrasenia, 'contraseña')
        );


        if (!empty($errores)) {
            $this->error(implode(' ', $errores));
        }





        $db = Database::getConexion();

        // Buscar usuario por email
        $stmt = $db->prepare(
            "SELECT id_usuario, email, contraseña FROM usuario WHERE email = ?"
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$usuario) {
            Auditoria::registrar($db, null, 'login_fallido', "email intentado: $email");
            $this->error('Email o contraseña incorrecto.');
        }




        $hashGuardado = $usuario['contraseña'];

        // Contraseña sin hash (legado) — pedir reseteo
        if (!str_starts_with($hashGuardado, '$2y$')) {
            $this->error("Tu contraseña necesita actualizarse. Usá '¿Olvidaste tu contraseña?' para crear una nueva.");
        }

        if (!password_verify($contrasenia, $hashGuardado)) {
            Auditoria::registrar($db, (int) $usuario['id_usuario'], 'login_fallido', 'contraseña incorrecta');
            $this->error('Email o contraseña incorrecto.');
        }

        $rol = $this->obtenerRol($db, (int) $usuario['id_usuario']);
        Auditoria::registrar($db, (int) $usuario['id_usuario'], 'login_exitoso');
        $this->crearSesion($usuario, $rol);
        $this->redirigirPorRol($rol);
        }


    private function obtenerRol(mysqli $db, int $idUsuario): string
    {
        $stmt = $db->prepare(
            "SELECT p.tipo_perfil
             FROM perfil p
             JOIN usuario u ON u.id_perfil = p.id_perfil
             WHERE u.id_usuario = ?"
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila['tipo_perfil'] ?? 'alumno';
    }

    private function crearSesion(array $usuario, string $rol): void
    {
        session_regenerate_id(true);
        $_SESSION['id_usuario']    = $usuario['id_usuario'];
        $_SESSION['email']         = $usuario['email'];
        $_SESSION['rol']           = $rol;
        $_SESSION['last_activity'] = time();
        $_SESSION['ip']            = $_SERVER['REMOTE_ADDR'];
        $_SESSION['ua']            = $_SERVER['HTTP_USER_AGENT'];
    }

    private function redirigirPorRol(string $rol): never
    {
        $destino = ($rol === 'bibliotecario')
            ? '../views/menu.php'
            : '../views/menu_usuario.php';
        header("Location: $destino");
        exit;
    }

    private function configurarSesionSegura(): void
    {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('session.use_strict_mode', 1);
        ini_set('session.gc_maxlifetime', 7200);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function error(string $mensaje): never
    {
        header('Location: ../views/index.php?error=' . urlencode($mensaje));
        exit;
    }
}
