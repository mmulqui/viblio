<?php
require_once("conexion.php");

$objeto = new conexion;
$conexion = $objeto->conectar();

$sql = "call registrar_libro('".$_POST["titulo"]."','".$_POST["edicion"]."','".$_POST["anio"]."','".$_POST["isbn"]."',1,'".$_POST["autor"]."','".$_POST["editorial"]."','".$_POST["categoria"]."','".$_POST["genero"]."')";
if ($conexion->query($sql)) {
    echo "<script>
            alert('Libro agregado exitosamente');
            window.location.href='menu.php?tab=libros';
        </script>";
} else {
    echo "<script>
            alert('Error al agregar libro: " . $conexion->error . "');
            window.history.back();
        </script>";
}
$objeto->desconectar($conexion);