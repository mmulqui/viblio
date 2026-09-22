<?php

class Auditoria {

    public static function registrar(mysqli $db, ?int $idUsuario, string $modulo, string $accion, string $detalle = ''): void {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $fecha = date('Y-m-d H:i:s');

        $stmt = $db->prepare(
            "INSERT INTO auditoria (id_usuario, modulo, accion, detalle, ip, fecha) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('isssss', $idUsuario, $modulo, $accion, $detalle, $ip, $fecha);
        $stmt->execute();
        $stmt->close();
    }
}