<?php
require_once("../controlers/auth.php");
verificarRol(["bibliotecario"]);
require_once("../models/conexion.php");

$objeto = new conexion();
$conexion = $objeto->conectar();
$isbn = mysqli_real_escape_string($conexion, $_GET["isbn"]);
$resultado = $conexion->query("select id_libro from libro where isbn ='$isbn';");

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $id_libro = $row["id_libro"];

    $conexion->query("delete from rela_aut_lib where id_libro = '$id_libro'");
    $conexion->query("delete from rela_edit_lib where id_libro = '$id_libro'");
    $conexion->query("delete from rela_cat_lib_gen where id_libro = '$id_libro'");

     if ($conexion->query("DELETE FROM libro WHERE isbn = '$isbn'")) {
        $objeto->desconectar($conexion);
        $_SESSION['alerta'] = ['tipo' => 'success', 'titulo' => '¡Éxito!', 'msg' => 'Libro eliminado correctamente.'];
        header("Location: ../views/menu.php");
        exit;
    } else {
        $objeto->desconectar($conexion);
        $_SESSION['alerta'] = ['tipo' => 'error', 'titulo' => 'Error', 'msg' => 'No se pudo eliminar el libro.'];
        header("Location: ../views/menu.php");
        exit;
    }
} else {
    $objeto->desconectar($conexion);
    $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'No se encontró un libro con ese ISBN.'];
    header("Location: ../views/menu.php");
    exit;
}
?>