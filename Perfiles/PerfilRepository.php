<?php
require_once dirname(__DIR__) . '/Core/Database.php';

/**
 * PerfilRepository — consultas a la BD para perfiles/roles.
 */
class PerfilRepository
{
    private mysqli $db;

    // IDs de perfiles base del sistema que no se pueden eliminar
    private const PERFILES_SISTEMA = [1, 2, 3];

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // ─── Lectura ──────────────────────────────────────────────────────────────

    /** Lista todos los perfiles ordenados por id. */
    public function listarTodos(): array
    {
        return $this->db
            ->query("SELECT id_perfil, tipo_perfil, activo FROM perfil ORDER BY id_perfil")
            ->fetch_all(MYSQLI_ASSOC);
    }

    /** Busca un perfil por su nombre/tipo. */
    public function obtenerPorTipoPerfil(string $tipoPerfil): ?array
    {
        $stmt = $this->db->prepare("SELECT id_perfil, tipo_perfil, activo FROM perfil WHERE tipo_perfil = ?");
        $stmt->bind_param('s', $tipoPerfil);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila ?: null;
    }

    /** Verifica si ya existe un perfil con ese nombre (excluyendo un id dado). */
    public function nombreExiste(string $tipoPerfil, ?int $excluirId = null): bool
    {
        if ($excluirId !== null) {
            $stmt = $this->db->prepare("SELECT id_perfil FROM perfil WHERE tipo_perfil = ? AND id_perfil != ?");
            $stmt->bind_param('si', $tipoPerfil, $excluirId);
        } else {
            $stmt = $this->db->prepare("SELECT id_perfil FROM perfil WHERE tipo_perfil = ?");
            $stmt->bind_param('s', $tipoPerfil);
        }
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    // ─── Escritura ────────────────────────────────────────────────────────────

    public function agregar(string $tipoPerfil): bool
    {
        $stmt = $this->db->prepare("INSERT INTO perfil (tipo_perfil, activo) VALUES (?, 1)");
        $stmt->bind_param('s', $tipoPerfil);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function modificar(int $idPerfil, string $tipoPerfil): bool
    {
        $stmt = $this->db->prepare("UPDATE perfil SET tipo_perfil = ? WHERE id_perfil = ?");
        $stmt->bind_param('si', $tipoPerfil, $idPerfil);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /** Baja lógica. Retorna false si es un perfil del sistema. */
    public function darBajaLogica(int $idPerfil): bool
    {
        if (in_array($idPerfil, self::PERFILES_SISTEMA, true)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE perfil SET activo = 0 WHERE id_perfil = ?");
        $stmt->bind_param('i', $idPerfil);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function esPerfilDelSistema(int $idPerfil): bool
    {
        return in_array($idPerfil, self::PERFILES_SISTEMA, true);
    }
}
