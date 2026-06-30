<?php
require_once dirname(__DIR__) . '/Core/Database.php';

/**
 * ModuloRepository — consultas a BD para módulos de perfiles y usuarios.
 */
class ModuloRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // ─── Por perfil ───────────────────────────────────────────────────────────

    /** Devuelve los módulos de un perfil con su estado activo/inactivo. */
    public function obtenerPorPerfil(int $idPerfil): array
    {
        $stmt = $this->db->prepare(
            "SELECT mc.id_modulo, mc.nombre, mc.clave, pm.activo
             FROM modulos_config mc
             JOIN perfil_modulos pm ON mc.id_modulo = pm.id_modulo
             WHERE pm.id_perfil = ?
             ORDER BY mc.id_modulo"
        );
        $stmt->bind_param('i', $idPerfil);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }

    /** Actualiza los módulos activos de un perfil. */
    public function guardarPorPerfil(int $idPerfil, array $idsActivos): bool
    {
        // Desactivar todos
        $stmt = $this->db->prepare("UPDATE perfil_modulos SET activo = 0 WHERE id_perfil = ?");
        $stmt->bind_param('i', $idPerfil);
        $stmt->execute();
        $stmt->close();

        // Activar los seleccionados
        foreach ($idsActivos as $idModulo) {
            $id   = (int) $idModulo;
            $stmt = $this->db->prepare(
                "UPDATE perfil_modulos SET activo = 1 WHERE id_perfil = ? AND id_modulo = ?"
            );
            $stmt->bind_param('ii', $idPerfil, $id);
            $stmt->execute();
            $stmt->close();
        }
        return true;
    }

    // ─── Por usuario ──────────────────────────────────────────────────────────

    /** Devuelve los módulos de un usuario con su estado activo/inactivo. */
    public function obtenerPorUsuario(int $idUsuario): array
    {
        $stmt = $this->db->prepare(
            "SELECT mc.id_modulo, mc.nombre, mc.clave, um.activo
             FROM modulos_config mc
             JOIN usuario_modulos um ON mc.id_modulo = um.id_modulo
             WHERE um.id_usuario = ?
             ORDER BY mc.id_modulo"
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }

    /** Devuelve los módulos activos de un usuario como mapa clave => bool. */
    public function obtenerMapaActivosPorUsuario(int $idUsuario): array
    {
        $filas = $this->obtenerPorUsuario($idUsuario);
        $mapa  = [];
        foreach ($filas as $fila) {
            $mapa[$fila['clave']] = (bool) $fila['activo'];
        }
        return $mapa;
    }

    /** Actualiza los módulos activos de un usuario. */
    public function guardarPorUsuario(int $idUsuario, array $idsActivos): bool
    {
        $stmt = $this->db->prepare("UPDATE usuario_modulos SET activo = 0 WHERE id_usuario = ?");
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $stmt->close();

        foreach ($idsActivos as $idModulo) {
            $id   = (int) $idModulo;
            $stmt = $this->db->prepare(
                "UPDATE usuario_modulos SET activo = 1 WHERE id_usuario = ? AND id_modulo = ?"
            );
            $stmt->bind_param('ii', $idUsuario, $id);
            $stmt->execute();
            $stmt->close();
        }
        return true;
    }
}
