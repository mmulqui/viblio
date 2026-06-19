<?php
require_once("../controlers/auth.php");
verificarRol(['bibliotecario']);
require_once("../models/conexion.php");

header('Content-Type: application/json');

if (isset($_GET['id_perfil'])) {
    $id_perfil = (int)$_GET['id_perfil'];

    $objeto   = new conexion();
    $conexion = $objeto->conectar();

    $sql = "SELECT mc.id_modulo, mc.nombre, mc.clave, pm.activo
            FROM modulos_config mc
            JOIN perfil_modulos pm ON mc.id_modulo = pm.id_modulo
            WHERE pm.id_perfil = ?
            ORDER BY mc.id_modulo";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_perfil);
    $stmt->execute();
    $result = $stmt->get_result();

    $modulos = [];
    while ($fila = $result->fetch_assoc()) {
        $modulos[] = $fila;
    }

    $stmt->close();
    $objeto->desconectar($conexion);

    echo json_encode(['success' => true, 'modulos' => $modulos]);
} else {
    echo json_encode(['success' => false, 'message' => 'id_perfil no proporcionado']);
}
?>