<?php
require_once("../controlers/auth.php");
verificarRol(["bibliotecario"]);
require_once("../models/conexion.php");

$objeto = new conexion();
$conexion = $objeto->conectar();
$dni = mysqli_real_escape_string($conexion, $_GET["dni"]);
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

    $objeto->desconectar($conexion);
    $_SESSION['alerta'] = ['tipo' => 'success', 'titulo' => '¡Éxito!', 'msg' => 'Usuario eliminado correctamente.'];
    header("Location: ../views/menu.php");
    exit;
} else {
   $objeto->desconectar($conexion);
    $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'No se encontró una persona con ese DNI.'];
    header("Location: ../views/menu.php");
    exit;
}
?>