<?php
require_once dirname(__DIR__) . '/Core/Database.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';
require_once dirname(__DIR__) . '/Multas/MultaRepository.php';

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
     * Crea una reserva pendiente. Una reserva aparta un ejemplar durante HORAS_RESERVA horas:
     * se valida DENTRO de una transacción con bloqueo de fila (FOR UPDATE) que queden ejemplares
     * sin apartar, que el usuario no tenga ya una reserva pendiente de ese libro y que no supere
     * su límite (préstamos activos + reservas pendientes). Así dos pedidos simultáneos no se pisan.
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

            // Sanción vigente (multa): mientras dure, no puede reservar, sea cual sea su rol.
            $sancionHasta = (new MultaRepository())->sancionVigenteHasta($idUsuario);
            if ($sancionHasta !== null) {
                $this->db->rollback();
                return [
                    'ok'         => false,
                    'error'      => 'El usuario tiene una sanción vigente hasta el '
                                    . date('d/m/Y H:i', strtotime($sancionHasta)) . ' y no puede reservar libros.',
                    'id_reserva' => null,
                ];
            }

            // Ejemplares en estantería (la fila del libro ya está bloqueada por esta transacción).
            $stmt = $this->db->prepare("SELECT stock FROM libro WHERE id_libro = ?");
            $stmt->bind_param('i', $idLibro);
            $stmt->execute();
            $stock = (int) $stmt->get_result()->fetch_assoc()['stock'];
            $stmt->close();

            // Reservas pendientes vigentes de este libro (cada una aparta un ejemplar).
            $pendiente = self::ESTADO_PENDIENTE;
            $horas     = PrestamoRepository::HORAS_RESERVA;
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) AS total, COALESCE(SUM(id_usuario = ?), 0) AS mias
                 FROM reserva
                 WHERE id_libro = ? AND id_estado = ? AND fecha_solicitud > DATE_SUB(NOW(), INTERVAL ? HOUR)"
            );
            $stmt->bind_param('iiii', $idUsuario, $idLibro, $pendiente, $horas);
            $stmt->execute();
            $apartados = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ((int) $apartados['mias'] > 0) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'Ya tenés una reserva pendiente de ese libro.', 'id_reserva' => null];
            }
            if ($stock < 1) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'No hay ejemplares disponibles de ese libro.', 'id_reserva' => null];
            }
            if ((int) $apartados['total'] >= $stock) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'Los ejemplares que quedan ya están reservados. Probá de nuevo más tarde.', 'id_reserva' => null];
            }

            // Límite del usuario: préstamos activos + reservas pendientes. Se bloquea su fila para que
            // dos reservas simultáneas no lo burlen (el perfil se lee sin bloqueo, igual que en préstamos).
            $stmt = $this->db->prepare("SELECT id_perfil FROM usuario WHERE id_usuario = ? FOR UPDATE");
            $stmt->bind_param('i', $idUsuario);
            $stmt->execute();
            $filaUsuario = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$filaUsuario) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'El usuario no existe.', 'id_reserva' => null];
            }
            $stmt = $this->db->prepare("SELECT tipo_perfil FROM perfil WHERE id_perfil = ?");
            $stmt->bind_param('i', $filaUsuario['id_perfil']);
            $stmt->execute();
            $tipoPerfil = $stmt->get_result()->fetch_assoc()['tipo_perfil'] ?? '';
            $stmt->close();

            $limite = PrestamoRepository::limitePorPerfil($tipoPerfil);
            if ($limite !== null) {
                $estadoActivo = PrestamoRepository::ESTADO_ACTIVO;
                $stmt = $this->db->prepare(
                    "SELECT (SELECT COUNT(*) FROM prestamo WHERE id_usuario = ? AND id_estado = ?)
                          + (SELECT COUNT(*) FROM reserva
                             WHERE id_usuario = ? AND id_estado = ? AND fecha_solicitud > DATE_SUB(NOW(), INTERVAL ? HOUR)) AS total"
                );
                $stmt->bind_param('iiiii', $idUsuario, $estadoActivo, $idUsuario, $pendiente, $horas);
                $stmt->execute();
                $total = (int) $stmt->get_result()->fetch_assoc()['total'];
                $stmt->close();
                if ($total >= $limite) {
                    $this->db->rollback();
                    return [
                        'ok'         => false,
                        'error'      => 'Alcanzaste el límite de ' . $limite . ' entre préstamos activos y reservas pendientes.',
                        'id_reserva' => null,
                    ];
                }
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

    /** Una reserva por id (con el usuario y el libro), o null. */
    public function obtenerPorId(int $idReserva): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM reserva WHERE id_reserva = ? LIMIT 1");
        $stmt->bind_param('i', $idReserva);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila ?: null;
    }

    /** Cantidad de ejemplares apartados hoy por reservas pendientes vigentes: [id_libro => cantidad]. */
    public function vigentesPorLibro(): array
    {
        $pendiente = self::ESTADO_PENDIENTE;
        $horas     = PrestamoRepository::HORAS_RESERVA;
        $stmt = $this->db->prepare(
            "SELECT id_libro, COUNT(*) AS n FROM reserva
             WHERE id_estado = ? AND fecha_solicitud > DATE_SUB(NOW(), INTERVAL ? HOUR)
             GROUP BY id_libro"
        );
        $stmt->bind_param('ii', $pendiente, $horas);
        $stmt->execute();
        $mapa = [];
        foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $f) {
            $mapa[(int) $f['id_libro']] = (int) $f['n'];
        }
        $stmt->close();
        return $mapa;
    }

    /** Reservas pendientes vigentes de un usuario (cuentan dentro de su límite de préstamos). */
    public function contarVigentesPorUsuario(int $idUsuario): int
    {
        $pendiente = self::ESTADO_PENDIENTE;
        $horas     = PrestamoRepository::HORAS_RESERVA;
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS n FROM reserva
             WHERE id_usuario = ? AND id_estado = ? AND fecha_solicitud > DATE_SUB(NOW(), INTERVAL ? HOUR)"
        );
        $stmt->bind_param('iii', $idUsuario, $pendiente, $horas);
        $stmt->execute();
        $n = (int) $stmt->get_result()->fetch_assoc()['n'];
        $stmt->close();
        return $n;
    }

    /** Pasa a "cancelada" las reservas pendientes que superaron HORAS_RESERVA sin retirarse. */
    public function cancelarVencidas(): int
    {
        $cancelada = self::ESTADO_CANCELADA;
        $pendiente = self::ESTADO_PENDIENTE;
        $horas     = PrestamoRepository::HORAS_RESERVA;
        $stmt = $this->db->prepare(
            "UPDATE reserva SET id_estado = ?
             WHERE id_estado = ? AND fecha_solicitud <= DATE_SUB(NOW(), INTERVAL ? HOUR)"
        );
        $stmt->bind_param('iii', $cancelada, $pendiente, $horas);
        $stmt->execute();
        $n = $stmt->affected_rows;
        $stmt->close();
        return max(0, $n);
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