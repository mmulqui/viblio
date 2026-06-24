<?php
require_once("../models/conexion.php");

session_start();

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 7200); // 2 horas

if (isset($_SESSION["id_usuario"])) {
    // Si ya está logueado, redirigir según su rol
    if (($_SESSION["rol"] ?? "") === "bibliotecario") {
        header("Location: ../views/menu.php");
    } else {
        header("Location: ../views/menu_usuario.php");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email       = $_POST["email"];
    $contrasenia = $_POST["contrasenia"];

    $objeto   = new conexion;
    $conexion = $objeto->conectar();

    $sql     = "SELECT id_usuario, email, contraseña FROM usuario WHERE email = ?";
    $consulta = $conexion->prepare($sql);
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows === 1) {
        $usuario     = $resultado->fetch_assoc();
        $hashGuardado = $usuario["contraseña"];

        $estaHasheada = str_starts_with($hashGuardado, '$2y$');

        if (!$estaHasheada) {
            $objeto->desconectar($conexion);
            if ($contrasenia === $hashGuardado) {
                redirigirError("Tu contraseña necesita ser actualizada. Por favor, usá la opción '¿Olvidaste tu contraseña?' para crear una nueva.");
            } else {
                redirigirError("Email o contraseña incorrecto");
            }
        }

        if (password_verify($contrasenia, $hashGuardado)) {
            
            $sqlPerfil = "SELECT p.tipo_perfil FROM perfil p JOIN usuario u ON u.id_perfil = p.id_perfil WHERE u.id_usuario = ?";
            $stmtPerfil = $conexion->prepare($sqlPerfil);
            $stmtPerfil->bind_param("i", $usuario["id_usuario"]);
            $stmtPerfil->execute();
            $resPerfil  = $stmtPerfil->get_result()->fetch_assoc();
            $tipoPerfil = $resPerfil["tipo_perfil"] ?? "alumno";            

            session_regenerate_id(true);

            $_SESSION["id_usuario"]    = $usuario["id_usuario"];
            $_SESSION["email"]         = $usuario["email"];
            $_SESSION["rol"]           = $tipoPerfil;
            $_SESSION["last_activity"] = time();
            $_SESSION["ip"]            = $_SERVER["REMOTE_ADDR"];
            $_SESSION["ua"]            = $_SERVER["HTTP_USER_AGENT"];

            $objeto->desconectar($conexion);
            
            if ($tipoPerfil === "bibliotecario") {
                header("Location: ../views/menu.php");
            } else {
                header("Location: ../views/menu_usuario.php");
            }

            exit();
        } else {
            $objeto->desconectar($conexion);
            redirigirError("Email o contraseña incorrecto");
        }

    } else {
        $objeto->desconectar($conexion);
        redirigirError("Email o contraseña incorrecto");
    }
}

function redirigirError(string $mensaje): void {
    header("Location: ../views/index.php?error=" . urlencode($mensaje));
    exit();
}
?>