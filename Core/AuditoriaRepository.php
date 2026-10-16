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
            "SELECT a.modulo, a.accion, a.detalle, a.fecha, u.email
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

    /** Módulos distintos que figuran en la auditoría (para el select del filtro). */
    public function listarModulos(): array
    {
        $res = $this->db->query("SELECT DISTINCT modulo FROM auditoria WHERE modulo <> '' ORDER BY modulo");
        return array_column($res->fetch_all(MYSQLI_ASSOC), 'modulo');
    }

    /**
     * Arma el WHERE de los filtros. Devuelve [sql, tipos, parámetros].
     * Se comparte entre contarFiltrado() y listarFiltrado() para que ambos filtren igual.
     */
    private function condiciones(string $modulo, string $texto, string $desde, string $hasta): array
    {
        $where  = [];
        $tipos  = '';
        $params = [];

        if ($modulo !== '') {
            $where[]  = 'a.modulo = ?';
            $tipos   .= 's';
            $params[] = $modulo;
        }
        if ($texto !== '') {
            $where[] = '(a.accion LIKE ? OR a.detalle LIKE ? OR u.email LIKE ?)';
            $tipos  .= 'sss';
            $like    = "%$texto%";
            array_push($params, $like, $like, $like);
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde)) {
            $where[]  = 'a.fecha >= ?';
            $tipos   .= 's';
            $params[] = "$desde 00:00:00";
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta)) {
            $where[]  = 'a.fecha <= ?';
            $tipos   .= 's';
            $params[] = "$hasta 23:59:59";
        }

        return [$where ? ' WHERE ' . implode(' AND ', $where) : '', $tipos, $params];
    }

    /** Cantidad total de registros que cumplen los filtros (para la paginación). */
    public function contarFiltrado(string $modulo = '', string $texto = '', string $desde = '', string $hasta = ''): int
    {
        [$where, $tipos, $params] = $this->condiciones($modulo, $texto, $desde, $hasta);

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total
             FROM auditoria a
             LEFT JOIN usuario u ON u.id_usuario = a.id_usuario" . $where
        );
        if ($params) {
            $stmt->bind_param($tipos, ...$params);
        }
        $stmt->execute();
        $total = (int) $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        return $total;
    }

    /**
     * Lista una página de registros de auditoría aplicando los filtros que vengan con valor.
     *
     * @param string $modulo Módulo exacto (ej. "Préstamos"). Vacío = todos.
     * @param string $texto  Busca en acción, detalle y email del usuario.
     * @param string $desde  Fecha inicial YYYY-MM-DD (inclusive). Inválida o vacía = sin límite.
     * @param string $hasta  Fecha final YYYY-MM-DD (inclusive). Inválida o vacía = sin límite.
     * @param int    $limite Filas por página.
     * @param int    $offset Filas a saltear (página actual).
     */
    public function listarFiltrado(
        string $modulo = '',
        string $texto  = '',
        string $desde  = '',
        string $hasta  = '',
        int    $limite = 50,
        int    $offset = 0
    ): array {
        [$where, $tipos, $params] = $this->condiciones($modulo, $texto, $desde, $hasta);

        $sql = "SELECT a.modulo, a.accion, a.detalle, a.fecha, u.email
                FROM auditoria a
                LEFT JOIN usuario u ON u.id_usuario = a.id_usuario"
             . $where
             . " ORDER BY a.fecha DESC, a.id_auditoria DESC
                LIMIT ? OFFSET ?";

        $tipos   .= 'ii';
        $params[] = max(1, $limite);
        $params[] = max(0, $offset);

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $filas;
    }
}





?>