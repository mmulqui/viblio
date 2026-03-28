<?php
require_once("conexion.php");

header('Content-Type: application/json');

if(isset($_GET['isbn'])) {
    $objeto = new conexion();
    $conexion = $objeto->conectar();
    
    $isbn = mysqli_real_escape_string($conexion, $_GET['isbn']);
    
    $sql = "SELECT libro.id_libro,
                   libro.isbn,
                   libro.titulo,
                   libro.edicion,
                   libro.anio_publicacion,
                   libro.estado,
                   autor.nombre AS autor,
                   editorial.nombre AS editorial,
                   categoria.nombre AS categoria,
                   genero.nombre AS genero
            FROM libro
            LEFT JOIN rela_aut_lib ON libro.id_libro = rela_aut_lib.id_libro
            LEFT JOIN autor ON rela_aut_lib.id_autor = autor.id_autor
            LEFT JOIN rela_edit_lib ON libro.id_libro = rela_edit_lib.id_libro
            LEFT JOIN editorial ON rela_edit_lib.id_editorial = editorial.id_editorial
            LEFT JOIN rela_cat_lib_gen ON libro.id_libro = rela_cat_lib_gen.id_libro
            LEFT JOIN categoria ON rela_cat_lib_gen.id_categoria = categoria.id_categoria
            LEFT JOIN genero ON rela_cat_lib_gen.id_genero = genero.id_genero
            WHERE libro.isbn = '$isbn'
            LIMIT 1";
    
    $result = $conexion->query($sql);
    
    if($result && $result->num_rows > 0) {
        $libro = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'libro' => $libro
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Libro no encontrado'
        ]);
    }
    
    $objeto->desconectar($conexion);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ISBN no proporcionado'
    ]);
}
?>