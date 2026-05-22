<?php
require_once("../models/conexion.php");

$objeto = new conexion();
$conexion = $objeto->conectar();
$dni = $_GET["dni"];
$resultado = $conexion->query("select id_persona from persona where dni ='$dni';");

if ($resultado && $resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $id_persona = $fila["id_persona"];

    $resUsuario = $conexion->query("SELECT id_usuario FROM usuario WHERE persona_id_persona = '$id_persona'");

    if ($resUsuario && $resUsuario->num_rows > 0) {

        $filaU = $resUsuario->fetch_assoc();
        $id_usuario = $filaU["id_usuario"];

        $conexion->query("UPDATE usuario SET activo = 0 WHERE id_usuario = '$id_usuario'");
    }

    echo "<script>
            alert('Usuario eliminado exitosamente');
            window.location.href='../views/menu.php?tab=usuario';
        </script>";
} else {
    echo "<script>
            alert('No se encontró una persona con ese DNI');
            window.location.href='../views/menu.php?tab=usuario';
        </script>";
}

$objeto->desconectar($conexion);
?>