<?php
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"]== "POST"){
    $email = $_POST["email"];
    $contrasenia = $_POST["contrasenia"];

    $objeto = new conexion;
    $conexion = $objeto->conectar();

    $sql = "select id_usuario, email, contraseña from usuario where email = ?";
    $consulta = $conexion->prepare($sql);
    $consulta->bind_param("s",$email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows === 1){
        $usuario = $resultado->fetch_assoc();

        if ($contrasenia === $usuario["contraseña"]){
            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["email"] = $usuario["email"];

            header("Location: menu.php");
            exit();

        } else {
            header("Location: index.php?error=" . urlencode("Contraseña incorrecta"));
            exit();
        }
    } else {
        header("Location: index.php?error=" . urlencode("El email no está registrado"));
        exit();
    }
    $objeto->desconectar($conexion);
}
?>