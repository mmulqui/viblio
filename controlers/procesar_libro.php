<?php
require_once("../controlers/auth.php");
verificarRol(["bibliotecario"]);
require_once("../models/conexion.php");

$objeto = new conexion;
$conexion = $objeto->conectar();

titulo     = mysqli_real_escape_string($conexion, $_POST["titulo"]);
$edicion    = mysqli_real_escape_string($conexion, $_POST["edicion"]);
$anio       = mysqli_real_escape_string($conexion, $_POST["anio"]);
$isbn       = mysqli_real_escape_string($conexion, $_POST["isbn"]);
$autor      = mysqli_real_escape_string($conexion, $_POST["autor"]);
$editorial  = mysqli_real_escape_string($conexion, $_POST["editorial"]);
$categoria  = mysqli_real_escape_string($conexion, $_POST["categoria"]);
$genero     = mysqli_real_escape_string($conexion, $_POST["genero"]);

$sql = "CALL registrar_libro('$titulo','$edicion','$anio','$isbn',1,'$autor','$editorial','$categoria','$genero')"; 

if ($conexion->query($sql)) {
    objeto->desconectar($conexion);
    $_SESSION['alerta'] = ['tipo' => 'success', 'titulo' => '¡Éxito!', 'msg' => 'Libro agregado correctamente.'];
    header("Location: ../views/menu.php");
    exit;
} else {
    $error = $conexion->error;
    $objeto->desconectar($conexion);
    $_SESSION['alerta'] = ['tipo' => 'error', 'titulo' => 'Error', 'msg' => 'No se pudo agregar el libro: ' . $error];
    header("Location: ../views/menu.php");
    exit;
}