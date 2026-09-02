<?php

require_once dirname(__DIR__) . '/Core/Database.php';

/**
 * AuditoriaRepository — consultas a la BD para el historial de auditoría.
 */
class AuditoriaRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    /** Lista los últimos registros de auditoría, con el email del usuario. */
    public function listarUltimos(int $limite = 50): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.accion, a.fecha, u.email
             FROM auditoria a
             LEFT JOIN usuario u ON u.id_usuario = a.id_usuario
             ORDER BY a.fecha DESC
             LIMIT ?"
        );
        $stmt->bind_param('i', $limite);
        $stmt->execute();
        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $filas;
    }
}





?>