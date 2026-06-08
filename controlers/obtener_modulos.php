<?php
require_once("../controlers/auth.php");
verificarRol(['bibliotecario']);
require_once("../models/conexion.php");

header('Content-Type: application/json');

if (isset($_GET['id_usuario'])) {
    $id_usuario = (int)$_GET['id_usuario'];

    $objeto   = new conexion();
    $conexion = $objeto->conectar();

    $sql = "SELECT mc.id_modulo, mc.nombre, mc.clave, um.activo
            FROM modulos_config mc
            JOIN usuario_modulos um ON mc.id_modulo = um.id_modulo
            WHERE um.id_usuario = ?
            ORDER BY mc.id_modulo";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $modulos = [];
    while ($fila = $result->fetch_assoc()) {
        $modulos[] = $fila;
    }

    $objeto->desconectar($conexion);

    echo json_encode(['success' => true, 'modulos' => $modulos]);
} else {
    echo json_encode(['success' => false, 'message' => 'id_usuario no proporcionado']);
}
?>