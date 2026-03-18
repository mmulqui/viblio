<?php
require_once("conexion.php");

$objeto = new conexion;
$conexion = $objeto->conectar();

$sql = "call registrar_alumno('".$_POST["nombre"]."','".$_POST["apellido"]."','".$_POST["fecha_nacimiento"]."','".$_POST["dni"]."','".$_POST["email"]."','".$_POST["contrasenia"]."')";
if ($conexion->query($sql)) {
    echo "<script>
            alert('Usuario agregado exitosamente');
            window.location.href='menu.php';
        </script>";
} else {
    echo "<script>
            alert('Error al agregar usuario: " . $conexion->error . "');
            window.history.back();
        </script>";
}
$objeto->desconectar($conexion);