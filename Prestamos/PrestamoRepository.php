<?php
require_once dirname(__DIR__) . '/Core/Database.php';

/**
 * PrestamoRepository — todas las consultas a la BD relacionadas con préstamos.
 */
class PrestamoRepository
{
    public const ESTADO_ACTIVO   = 1; // ids de estado_prestamo (ver script de ajustes)
    public const ESTADO_DEVUELTO = 2;
    public const ESTADO_VENCIDO  = 3;

    public const LIMITE_PRESTAMOS_ALUMNO = 3;
    public const DIAS_PRESTAMO           = 7;

    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // ─── Consultas de lectura ─────────────────────────────────────────────────

    /** Lista los préstamos de un usuario, con título del libro y descripción del estado. */
    public function listarPorUsuario(int $idUsuario): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, l.titulo, ep.descripcion AS estado_descripcion
             FROM prestamo p
             JOIN libro l            ON l.id_libro  = p.id_libro
             JOIN estado_prestamo ep ON ep.id_estado = p.id_estado
             WHERE p.id_usuario = ?
             ORDER BY p.fecha_prestamo DESC"
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $filas;
    }

    public function obtenerPorId(int $idPrestamo): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, l.titulo, ep.descripcion AS estado_descripcion
             FROM prestamo p
             JOIN libro l            ON l.id_libro  = p.id_libro
             JOIN estado_prestamo ep ON ep.id_estado = p.id_estado
             WHERE p.id_prestamo = ?
             LIMIT 1"
        );
        $stmt->bind_param('i', $idPrestamo);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila ?: null;
    }

    /** Cuenta los préstamos activos (no devueltos) de un usuario — para el límite de 3. */
    public function contarActivosPorUsuario(int $idUsuario): int
    {
        $estadoActivo = self::ESTADO_ACTIVO;
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM prestamo
             WHERE id_usuario = ? AND id_estado = ?"
        );
        $stmt->bind_param('ii', $idUsuario, $estadoActivo);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) $fila['total'];
    }

    /** Indica si un libro tiene actualmente un préstamo activo (sin devolver). */
    public function libroEstaPrestado(int $idLibro): bool
    {
        $estadoActivo = self::ESTADO_ACTIVO;
        $stmt = $this->db->prepare(
            "SELECT id_prestamo FROM prestamo
             WHERE id_libro = ? AND id_estado = ?
             LIMIT 1"
        );
        $stmt->bind_param('ii', $idLibro, $estadoActivo);
        $stmt->execute();
        $stmt->store_result();
        $prestado = $stmt->num_rows > 0;
        $stmt->close();
        return $prestado;
    }

    /**
     * Lista todos los préstamos del sistema, con datos del libro y del alumno.
     * Pensado para la vista del bibliotecario (con búsqueda opcional).
     */
    public function listarTodos(string $busqueda = '', bool $todos = false): array
    {
        $base = "SELECT p.*, l.titulo, l.isbn, per.nombre, per.apellido, per.dni,
                        ep.descripcion AS estado_descripcion
                 FROM prestamo p
                 JOIN libro l    ON l.id_libro  = p.id_libro
                 JOIN usuario u  ON u.id_usuario = p.id_usuario
                 JOIN persona per ON per.id_persona = u.persona_id_persona
                 JOIN estado_prestamo ep ON ep.id_estado = p.id_estado";

        if ($todos || $busqueda === '') {
            return $this->db->query($base . " ORDER BY p.fecha_prestamo DESC")->fetch_all(MYSQLI_ASSOC);
        }

        $stmt = $this->db->prepare(
            "$base WHERE l.titulo LIKE ? OR per.nombre LIKE ? OR per.apellido LIKE ? OR per.dni LIKE ?
             ORDER BY p.fecha_prestamo DESC"
        );
        $param = "%$busqueda%";
        $stmt->bind_param('ssss', $param, $param, $param, $param);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }

    // ─── Escritura ──────────────────────────────────────────────────────────

    /** Crea un préstamo nuevo: vencimiento a 7 días desde ahora, estado activo. */
    public function crear(int $idLibro, int $idUsuario): int
    {
        $codigoPrestamo = random_int(100000, 999999);
        $estadoActivo   = self::ESTADO_ACTIVO;
        $dias           = self::DIAS_PRESTAMO;

        $stmt = $this->db->prepare(
            "INSERT INTO prestamo
                (codigo_prestamo, fecha_prestamo, fecha_vencimieto, fecha_devolucion, id_libro, id_estado, id_usuario)
             VALUES
                (?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY), NULL, ?, ?, ?)"
        );
        $stmt->bind_param('iiiii', $codigoPrestamo, $dias, $idLibro, $estadoActivo, $idUsuario);
        $stmt->execute();
        $idPrestamo = $stmt->insert_id;
        $stmt->close();
        return $idPrestamo;
    }

    /** Marca un préstamo activo como devuelto. */
    public function registrarDevolucion(int $idPrestamo): bool
    {
        $estadoDevuelto = self::ESTADO_DEVUELTO;
        $estadoActivo   = self::ESTADO_ACTIVO;
        $stmt = $this->db->prepare(
            "UPDATE prestamo
             SET fecha_devolucion = NOW(), id_estado = ?
             WHERE id_prestamo = ? AND id_estado = ?"
        );
        $stmt->bind_param('iii', $estadoDevuelto, $idPrestamo, $estadoActivo);
        $ok = $stmt->execute() && $stmt->affected_rows > 0;
        $stmt->close();
        return $ok;
    }

    /** Marca como vencidos los préstamos activos cuya fecha de vencimiento ya pasó. Pensado para un cron/tarea programada. */
    public function marcarVencidos(): int
    {
        $estadoVencido = self::ESTADO_VENCIDO;
        $estadoActivo  = self::ESTADO_ACTIVO;
        $stmt = $this->db->prepare(
            "UPDATE prestamo
             SET id_estado = ?
             WHERE id_estado = ? AND fecha_vencimieto < NOW()"
        );
        $stmt->bind_param('ii', $estadoVencido, $estadoActivo);
        $stmt->execute();
        $afectados = $stmt->affected_rows;
        $stmt->close();
        return $afectados;
    }
}