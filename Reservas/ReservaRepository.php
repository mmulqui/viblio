<?php
require_once dirname(__DIR__) . '/Core/Database.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';

/**
 * ReservaRepository — todas las consultas a la BD relacionadas con reservas.
 */
class ReservaRepository
{
    public const ESTADO_PENDIENTE = 1; // ids de estado_reserva (ver script de ajustes)
    public const ESTADO_CUMPLIDA  = 2;
    public const ESTADO_CANCELADA = 3;

    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    /** Lista las reservas de un usuario, con título del libro y descripción del estado. */
    public function listarPorUsuario(int $idUsuario): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, l.titulo, er.descripcion AS estado_descripcion
             FROM reserva r
             JOIN libro l           ON l.id_libro  = r.id_libro
             JOIN estado_reserva er ON er.id_estado = r.id_estado
             WHERE r.id_usuario = ?
             ORDER BY r.fecha_solicitud DESC"
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $filas;
    }

    /**
     * Lista todas las reservas del sistema, con datos del libro y del alumno.
     * Pensado para la vista del bibliotecario (con búsqueda opcional).
     */
    public function listarTodos(string $busqueda = '', bool $todos = false): array
    {
        $base = "SELECT r.*, l.titulo, l.isbn, per.nombre, per.apellido, per.dni,
                        er.descripcion AS estado_descripcion
                 FROM reserva r
                 JOIN libro l    ON l.id_libro  = r.id_libro
                 JOIN usuario u  ON u.id_usuario = r.id_usuario
                 JOIN persona per ON per.id_persona = u.persona_id_persona
                 JOIN estado_reserva er ON er.id_estado = r.id_estado";

        if ($todos || $busqueda === '') {
            return $this->db->query($base . " ORDER BY r.fecha_solicitud DESC")->fetch_all(MYSQLI_ASSOC);
        }

        $stmt = $this->db->prepare(
            "$base WHERE l.titulo LIKE ? OR per.nombre LIKE ? OR per.apellido LIKE ? OR per.dni LIKE ?
             ORDER BY r.fecha_solicitud DESC"
        );
        $param = "%$busqueda%";
        $stmt->bind_param('ssss', $param, $param, $param, $param);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }

    /**
     * Crea una reserva pendiente, validando DENTRO de una transacción con
     * bloqueo de fila (FOR UPDATE) que el libro no esté prestado — así dos
     * pedidos simultáneos no se pisan.
     *
     * @return array{ok: bool, error: ?string, id_reserva: ?int}
     */
    public function crear(int $idLibro, int $idUsuario): array
    {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("SELECT id_libro FROM libro WHERE id_libro = ? FOR UPDATE");
            $stmt->bind_param('i', $idLibro);
            $stmt->execute();
            $stmt->store_result();
            $existeLibro = $stmt->num_rows > 0;
            $stmt->close();
            if (!$existeLibro) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'El libro no existe.', 'id_reserva' => null];
            }

            $estadoActivoPrestamo = PrestamoRepository::ESTADO_ACTIVO;
            $stmt = $this->db->prepare(
                "SELECT id_prestamo FROM prestamo WHERE id_libro = ? AND id_estado = ? FOR UPDATE"
            );
            $stmt->bind_param('ii', $idLibro, $estadoActivoPrestamo);
            $stmt->execute();
            $stmt->store_result();
            $yaPrestado = $stmt->num_rows > 0;
            $stmt->close();
            if ($yaPrestado) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'No se puede reservar un libro que ya está prestado.', 'id_reserva' => null];
            }

            $codigoReserva   = random_int(100000, 999999);
            $estadoPendiente = self::ESTADO_PENDIENTE;
            $stmt = $this->db->prepare(
                "INSERT INTO reserva
                    (fecha_solicitud, fecha_reserva, codigo_reserva, id_libro, id_estado, id_usuario)
                 VALUES
                    (NOW(), NOW(), ?, ?, ?, ?)"
            );
            $stmt->bind_param('iiii', $codigoReserva, $idLibro, $estadoPendiente, $idUsuario);
            $stmt->execute();
            $idReserva = $stmt->insert_id;
            $stmt->close();

            $this->db->commit();
            return ['ok' => true, 'error' => null, 'id_reserva' => $idReserva];
        } catch (Throwable $e) {
            $this->db->rollback();
            error_log('Error ReservaRepository::crear: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Ocurrió un error al registrar la reserva.', 'id_reserva' => null];
        }
    }

    /** Cancela una reserva pendiente. */
    public function cancelar(int $idReserva): bool
    {
        $estadoCancelada = self::ESTADO_CANCELADA;
        $estadoPendiente = self::ESTADO_PENDIENTE;
        $stmt = $this->db->prepare(
            "UPDATE reserva SET id_estado = ? WHERE id_reserva = ? AND id_estado = ?"
        );
        $stmt->bind_param('iii', $estadoCancelada, $idReserva, $estadoPendiente);
        $ok = $stmt->execute() && $stmt->affected_rows > 0;
        $stmt->close();
        return $ok;
    }

    /** Marca una reserva como cumplida (el alumno retiró el libro reservado). */
    public function cumplir(int $idReserva): bool
    {
        $estadoCumplida  = self::ESTADO_CUMPLIDA;
        $estadoPendiente = self::ESTADO_PENDIENTE;
        $stmt = $this->db->prepare(
            "UPDATE reserva SET id_estado = ? WHERE id_reserva = ? AND id_estado = ?"
        );
        $stmt->bind_param('iii', $estadoCumplida, $idReserva, $estadoPendiente);
        $ok = $stmt->execute() && $stmt->affected_rows > 0;
        $stmt->close();
        return $ok;
    }
}