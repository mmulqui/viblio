<?php
require_once dirname(__DIR__) . '/Core/Database.php';

/**
 * MultaRepository — acceso a datos de las multas (sanciones por tiempo).
 *
 * Una multa es una suspensión: mientras está vigente, el usuario no puede pedir
 * libros ni hacer reservas. "Vigente" y "cumplida" se calculan por fecha, no se guardan.
 */
class MultaRepository
{
    public const MOTIVO_RETRASO    = 'retraso';
    public const MOTIVO_RAYADO     = 'rayado';
    public const MOTIVO_MALTRATADO = 'maltratado';
    public const MOTIVO_ROTO       = 'roto';

    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // ─── Consultas ────────────────────────────────────────────────────────────

    /** Fecha (DATETIME de MySQL) hasta la que el usuario está sancionado, o null si no tiene sanción vigente. */
    public function sancionVigenteHasta(int $idUsuario): ?string
    {
        $stmt = $this->db->prepare(
            "SELECT MAX(fecha_fin) AS hasta
             FROM multa
             WHERE id_usuario = ? AND estado = 'activa' AND fecha_fin > NOW()"
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila['hasta'] ?? null;
    }

    /** Una multa con los datos del usuario y del libro (para auditoría y mensajes). */
    public function obtenerPorId(int $idMulta): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT m.*, p.nombre, p.apellido, p.dni
             FROM multa m
             JOIN usuario u ON u.id_usuario = m.id_usuario
             JOIN persona p ON p.id_persona = u.persona_id_persona
             WHERE m.id_multa = ?
             LIMIT 1"
        );
        $stmt->bind_param('i', $idMulta);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila ?: null;
    }

    /**
     * Lista las multas para la pestaña Multas.
     *
     * @param string $busqueda Busca en nombre, DNI, libro, motivo y observaciones.
     * @param string $estado   'vigente' | 'cumplida' | 'anulada' | '' (todas).
     */
    public function listar(string $busqueda = '', string $estado = ''): array
    {
        $where  = [];
        $tipos  = '';
        $params = [];

        if ($busqueda !== '') {
            $where[] = "(CONCAT(p.nombre, ' ', p.apellido) LIKE ? OR p.dni LIKE ? OR l.titulo LIKE ?
                         OR m.observaciones LIKE ? OR m.motivo LIKE ?)";
            $tipos  .= 'sssss';
            $like    = "%$busqueda%";
            array_push($params, $like, $like, $like, $like, $like);
        }

        switch ($estado) {
            case 'vigente':  $where[] = "m.estado = 'activa' AND m.fecha_fin > NOW()";  break;
            case 'cumplida': $where[] = "m.estado = 'activa' AND m.fecha_fin <= NOW()"; break;
            case 'anulada':  $where[] = "m.estado = 'anulada'";                         break;
        }

        $sql = "SELECT m.id_multa, m.motivo, m.dias_sancion, m.fecha_inicio, m.fecha_fin,
                       m.observaciones, m.fecha_registro,
                       p.nombre, p.apellido, p.dni, pf.tipo_perfil AS rol, l.titulo,
                       CASE WHEN m.estado = 'anulada' THEN 'anulada'
                            WHEN m.fecha_fin > NOW()  THEN 'vigente'
                            ELSE 'cumplida' END AS estado_calculado
                FROM multa m
                JOIN usuario u        ON u.id_usuario  = m.id_usuario
                JOIN persona p        ON p.id_persona  = u.persona_id_persona
                LEFT JOIN perfil pf   ON pf.id_perfil  = u.id_perfil
                LEFT JOIN prestamo pr ON pr.id_prestamo = m.id_prestamo
                LEFT JOIN libro l     ON l.id_libro    = pr.id_libro"
             . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
             . " ORDER BY m.fecha_registro DESC, m.id_multa DESC";

        $stmt = $this->db->prepare($sql);
        if ($params) {
            $stmt->bind_param($tipos, ...$params);
        }
        $stmt->execute();
        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $filas;
    }

    // ─── Escritura ────────────────────────────────────────────────────────────

    /**
     * Crea una multa. Las sanciones se acumulan: la nueva empieza cuando termina la
     * última sanción vigente del usuario (o ahora, si no tiene ninguna).
     * No abre transacción: la maneja quien la llama.
     *
     * @return int id de la multa creada
     */
    public function crear(
        int $idUsuario,
        ?int $idPrestamo,
        string $motivo,
        int $diasSancion,
        ?string $observaciones,
        ?int $idRegistrador
    ): int {
        $stmt = $this->db->prepare(
            "SELECT GREATEST(NOW(), COALESCE(MAX(fecha_fin), NOW())) AS inicio
             FROM multa
             WHERE id_usuario = ? AND estado = 'activa'"
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $inicio = $stmt->get_result()->fetch_assoc()['inicio'];
        $stmt->close();

        $stmt = $this->db->prepare(
            "INSERT INTO multa
                (id_usuario, id_prestamo, motivo, dias_sancion, fecha_inicio, fecha_fin, observaciones, id_registrador)
             VALUES
                (?, ?, ?, ?, ?, DATE_ADD(?, INTERVAL ? DAY), ?, ?)"
        );
        $stmt->bind_param(
            'iisissisi',
            $idUsuario, $idPrestamo, $motivo, $diasSancion, $inicio, $inicio, $diasSancion, $observaciones, $idRegistrador
        );
        $stmt->execute();
        $idMulta = $stmt->insert_id;
        $stmt->close();

        return $idMulta;
    }

    /** Anula una multa que todavía figura como activa. */
    public function anular(int $idMulta): bool
    {
        $stmt = $this->db->prepare("UPDATE multa SET estado = 'anulada' WHERE id_multa = ? AND estado = 'activa'");
        $stmt->bind_param('i', $idMulta);
        $ok = $stmt->execute() && $stmt->affected_rows > 0;
        $stmt->close();
        return $ok;
    }

    /**
     * Mantiene al día la columna "Nro de Multas" de la tabla Usuarios (alumno.numero_multas):
     * cantidad de multas no anuladas. Si el usuario no es alumno no actualiza nada.
     */
    public function recalcularContador(int $idUsuario): void
    {
        $stmt = $this->db->prepare(
            "UPDATE alumno
             SET numero_multas = (SELECT COUNT(*) FROM multa WHERE id_usuario = ? AND estado <> 'anulada')
             WHERE usuario_id_usuario = ?"
        );
        $stmt->bind_param('ii', $idUsuario, $idUsuario);
        $stmt->execute();
        $stmt->close();
    }
}