<?php
require_once("../controlers/auth.php");
verificarRol(['bibliotecario']);
require_once("../models/conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_perfil = isset($_POST['id_perfil']) ? (int)$_POST['id_perfil'] : 0;
    $modulos   = isset($_POST['modulos'])   ? $_POST['modulos']         : [];

    if ($id_perfil <= 0) {
        echo json_encode(['success' => false, 'message' => 'Perfil inválido']);
        exit;
    }

    $objeto   = new conexion();
    $conexion = $objeto->conectar();

    // Desactivar todos los módulos del perfil
    $stmt = $conexion->prepare("UPDATE perfil_modulos SET activo = 0 WHERE id_perfil = ?");
    $stmt->bind_param("i", $id_perfil);
    $stmt->execute();
    $stmt->close();

    // Activar solo los que vinieron marcados
    if (!empty($modulos)) {
        foreach ($modulos as $id_modulo) {
            $id_modulo = (int)$id_modulo;
            $stmt = $conexion->prepare(
                "UPDATE perfil_modulos SET activo = 1 WHERE id_perfil = ? AND id_modulo = ?"
            );
            $stmt->bind_param("ii", $id_perfil, $id_modulo);
            $stmt->execute();
            $stmt->close();
        }
    }

    $objeto->desconectar($conexion);
    echo json_encode(['success' => true, 'message' => 'Módulos del perfil actualizados correctamente']);

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>