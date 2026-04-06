<?php
require_once("../models/conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contrasenia = $_POST["contrasenia"];

    $objeto = new conexion;
    $conexion = $objeto->conectar();

    $sql = "SELECT id_usuario, email, contraseña FROM usuario WHERE email = ?";
    $consulta = $conexion->prepare($sql);
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $hashGuardado = $usuario["contraseña"];

        $estaHasheada = str_starts_with($hashGuardado, '$2y$');

        if (!$estaHasheada) {
            if ($contrasenia === $hashGuardado) {
                header("Location: /login_viblio/view_bibliotecario/views/index.php?error=" . urlencode("Tu contraseña necesita ser actualizada. Por favor, usá la opción '¿Olvidaste tu contraseña?' para crear una nueva."));
                exit();
            } else {
                header("Location: /login_viblio/view_bibliotecario/views/index.php?error=" . urlencode("Contraseña incorrecta"));
                exit();
            }
        } else {
            if (password_verify($contrasenia, $hashGuardado)) {
                $_SESSION["id_usuario"] = $usuario["id_usuario"];
                $_SESSION["email"] = $usuario["email"];

                header("Location: /login_viblio/view_bibliotecario/views/menu.php");
                exit();
            } else {
                header("Location: /login_viblio/view_bibliotecario/views/index.php?error=" . urlencode("Contraseña incorrecta"));
                exit();
            }
        }
    } else {
        header("Location: /login_viblio/view_bibliotecario/views/index.php?error=" . urlencode("El email no está registrado"));
        exit();
    }

    $objeto->desconectar($conexion);
}
?>