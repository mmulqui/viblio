<?php
require_once("conexion.php");

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

        
        $conexion->query("DELETE FROM alumno WHERE usuario_id_usuario = '$id_usuario'");
        $conexion->query("DELETE FROM bibliotecario WHERE usuario_id_usuario = '$id_usuario'");
        $conexion->query("DELETE FROM profesor WHERE usuario_id_usuario = '$id_usuario'");
        
        
        $conexion->query("DELETE FROM usuario WHERE id_usuario = '$id_usuario'");
    }

    $conexion->query("DELETE FROM persona WHERE id_persona = '$id_persona'");

    echo "<script>
            alert('Usuario eliminado exitosamente');
            window.location.href='menu.php?tab=usuario';
        </script>";
} else {
    echo "<script>
            alert('No se encontró una persona con ese DNI');
            window.location.href='menu.php?tab=usuario';
        </script>";
}

$objeto->desconectar($conexion);
?>