<?php
require_once("conexion.php");

$objeto = new conexion();
$conexion = $objeto->conectar();
$isbn = $_GET["isbn"];
$resultado = $conexion->query("select id_libro from libro where isbn ='$isbn';");

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $id_libro = $row["id_libro"];

    $conexion->query("delete from rela_aut_lib where id_libro = '$id_libro'");
    $conexion->query("delete from rela_edit_lib where id_libro = '$id_libro'");
    $conexion->query("delete from rela_cat_lib_gen where id_libro = '$id_libro'");

    $sql = "delete from libro where isbn = '$isbn'";

    if($conexion->query($sql)){
        echo "<script> 
        alert('Libro eliminado exitosamente');
        window.location.href='menu.php?tab=libros'; </script>";
    } else {
        echo "<script> 
        alert('Error al eliminar el libro');
        window.location.href='menu.php?tab=libros'; </script>";
    }
    $objeto->desconectar($conexion);
}