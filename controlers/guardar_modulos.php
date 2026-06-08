<?php
require_once("../controlers/auth.php");
verificarRol(['bibliotecario']);
require_once("../models/conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
    $modulos    = isset($_POST['modulos']) ? $_POST['modulos'] : [];

    if ($id_usuario <= 0) {
        echo json_encode(['success' => false, 'message' => 'Usuario inválido']);
        exit;
    }

    $objeto   = new conexion();
    $conexion = $objeto->conectar();

    // Primero desactivar todos los módulos del usuario
    $sql = "UPDATE usuario_modulos SET activo = 0 WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    // Luego activar solo los que vinieron marcados
    if (!empty($modulos)) {
        foreach ($modulos as $id_modulo) {
            $id_modulo = (int)$id_modulo;
            $sql = "UPDATE usuario_modulos SET activo = 1 WHERE id_usuario = ? AND id_modulo = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $id_usuario, $id_modulo);
            $stmt->execute();
        }
    }

    $objeto->desconectar($conexion);
    echo json_encode(['success' => true, 'message' => 'Módulos actualizados correctamente']);

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>