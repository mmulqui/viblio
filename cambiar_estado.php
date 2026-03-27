<?php
require_once("conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(isset($_POST['isbn']) && isset($_POST['estado'])) {
        
        $objeto = new conexion();
        $conexion = $objeto->conectar();
        
        $isbn = mysqli_real_escape_string($conexion, $_POST['isbn']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        
        if($estado != '0' && $estado != '1') {
            echo json_encode([
                'success' => false,
                'message' => 'Estado inválido'
            ]);
            exit;
        }
        
        $sql = "UPDATE libro SET estado = '$estado' WHERE isbn = '$isbn'";
        
        if($conexion->query($sql)) {
            $objeto->desconectar($conexion);
            echo json_encode([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } else {
            $error = $conexion->error;
            $objeto->desconectar($conexion);
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar: ' . $error
            ]);
        }
        
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan parámetros'
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
?>