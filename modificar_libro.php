<?php
require_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $objeto = new conexion();
    $conexion = $objeto->conectar();
    
    $isbn = mysqli_real_escape_string($conexion, $_POST['isbn']);
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $edicion = mysqli_real_escape_string($conexion, $_POST['edicion']);
    $anio = mysqli_real_escape_string($conexion, $_POST['anio']);
    $autor = mysqli_real_escape_string($conexion, $_POST['autor']);
    $editorial = mysqli_real_escape_string($conexion, $_POST['editorial']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $genero = mysqli_real_escape_string($conexion, $_POST['genero']);
    
    $sql = "CALL modificar_libro('$isbn', '$titulo', '$edicion', '$anio',1, '$autor', '$editorial', '$categoria', '$genero')";
    
    if ($conexion->query($sql)) {
        $objeto->desconectar($conexion);
        echo "<script>
                alert('Libro modificado exitosamente');
                window.location.href='menu.php?tab=libros';
              </script>";
    } else {
        $error = $conexion->error;
        $objeto->desconectar($conexion);
        echo "<script>
                alert('Error al modificar libro: " . addslashes($error) . "');
                window.history.back();
              </script>";
    }
    
} else {
    header("Location: menu.php");
    exit;
}
?>