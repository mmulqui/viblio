<?php

class Auditoria {

    public static function registrar(mysqli $db, ?int $idUsuario, string $accion, string $detalle = ''): void {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $fecha = date('Y-m-d H:i:s');

        $stmt = $db->prepare(
            "INSERT INTO auditoria (id_usuario, accion, detalle, ip, fecha) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('issss', $idUsuario, $accion, $detalle, $ip, $fecha);
        $stmt->execute();
        $stmt->close();
    }
}

