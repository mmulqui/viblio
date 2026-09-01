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

    /**
     * Crea un préstamo nuevo, validando disponibilidad y límite DENTRO de una
     * transacción con bloqueo de fila (FOR UPDATE), para que dos pedidos
     * simultáneos del mismo libro no pasen la validación al mismo tiempo.
     *
     * @return array{ok: bool, error: ?string, id_prestamo: ?int}
     */
    public function crear(int $idLibro, int $idUsuario): array
    {
        $this->db->begin_transaction();
        try {
            // Bloquea la fila del libro: cualquier otro pedido sobre este mismo
            // libro tiene que esperar a que esta transacción termine (commit o rollback).
            $stmt = $this->db->prepare("SELECT id_libro FROM libro WHERE id_libro = ? FOR UPDATE");
            $stmt->bind_param('i', $idLibro);
            $stmt->execute();
            $stmt->store_result();
            $existeLibro = $stmt->num_rows > 0;
            $stmt->close();
            if (!$existeLibro) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'El libro no existe.', 'id_prestamo' => null];
            }

            $estadoActivo = self::ESTADO_ACTIVO;

            // Con la fila del libro ya bloqueada, esta lectura es segura: nadie más
            // puede insertar un préstamo activo para este libro mientras esperamos.
            $stmt = $this->db->prepare(
                "SELECT id_prestamo FROM prestamo WHERE id_libro = ? AND id_estado = ? FOR UPDATE"
            );
            $stmt->bind_param('ii', $idLibro, $estadoActivo);
            $stmt->execute();
            $stmt->store_result();
            $yaPrestado = $stmt->num_rows > 0;
            $stmt->close();
            if ($yaPrestado) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'Ese libro ya está prestado.', 'id_prestamo' => null];
            }

            // Bloquea también la fila del usuario, para que dos préstamos simultáneos
            // del mismo alumno no burlen el límite de 3 contando "al mismo tiempo".
            $stmt = $this->db->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ? FOR UPDATE");
            $stmt->bind_param('i', $idUsuario);
            $stmt->execute();
            $stmt->store_result();
            $existeUsuario = $stmt->num_rows > 0;
            $stmt->close();
            if (!$existeUsuario) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'El usuario no existe.', 'id_prestamo' => null];
            }

            $stmt = $this->db->prepare(
                "SELECT COUNT(*) AS total FROM prestamo WHERE id_usuario = ? AND id_estado = ?"
            );
            $stmt->bind_param('ii', $idUsuario, $estadoActivo);
            $stmt->execute();
            $total = (int) $stmt->get_result()->fetch_assoc()['total'];
            $stmt->close();
            if ($total >= self::LIMITE_PRESTAMOS_ALUMNO) {
                $this->db->rollback();
                return [
                    'ok'          => false,
                    'error'       => 'Alcanzaste el límite de ' . self::LIMITE_PRESTAMOS_ALUMNO . ' préstamos simultáneos.',
                    'id_prestamo' => null,
                ];
            }

            $codigoPrestamo = random_int(100000, 999999);
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

            $this->db->commit();
            return ['ok' => true, 'error' => null, 'id_prestamo' => $idPrestamo];
        } catch (Throwable $e) {
            $this->db->rollback();
            error_log('Error PrestamoRepository::crear: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Ocurrió un error al registrar el préstamo.', 'id_prestamo' => null];
        }
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