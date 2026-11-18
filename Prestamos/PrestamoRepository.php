<?php
require_once dirname(__DIR__) . '/Multas/MultaRepository.php';
require_once dirname(__DIR__) . '/Core/Database.php';

/**
 * PrestamoRepository — todas las consultas a la BD relacionadas con préstamos.
 */
class PrestamoRepository
{
    public const ESTADO_ACTIVO   = 1; // ids de estado_prestamo (ver script de ajustes)
    public const ESTADO_DEVUELTO = 2;
    public const ESTADO_VENCIDO  = 3;

    /** Horas que una reserva pendiente aparta un ejemplar antes de vencer. */
    public const HORAS_RESERVA = 48;
    private const RESERVA_PENDIENTE = 1; // ids de estado_reserva (ReservaRepository tiene los mismos)
    private const RESERVA_CUMPLIDA  = 2;

    public const LIMITE_PRESTAMOS_ALUMNO = 3;
    public const DIAS_PRESTAMO           = 7;

    /**
     * Límite de préstamos simultáneos según el tipo de perfil (perfil.tipo_perfil).
     * null = sin límite. Cualquier perfil que no figure acá usa LIMITE_PRESTAMOS_ALUMNO.
     */
    public const LIMITES_POR_PERFIL = [
        'alumno'   => self::LIMITE_PRESTAMOS_ALUMNO,
        'profesor' => null,
    ];

    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    /** Devuelve el límite de préstamos simultáneos para un perfil (null = ilimitado). */
    public static function limitePorPerfil(string $tipoPerfil): ?int
    {
        return array_key_exists($tipoPerfil, self::LIMITES_POR_PERFIL)
            ? self::LIMITES_POR_PERFIL[$tipoPerfil]
            : self::LIMITE_PRESTAMOS_ALUMNO;
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
     * Crea un préstamo nuevo, validando stock y límite DENTRO de una
     * transacción con bloqueo de fila (FOR UPDATE), para que dos pedidos
     * simultáneos del mismo libro no pasen la validación al mismo tiempo.
     *
     * Con $idReserva el préstamo se crea al ENTREGAR esa reserva: se valida que siga pendiente y
     * vigente y que sea del mismo usuario y libro, y se marca cumplida en la misma transacción.
     * Los ejemplares apartados por reservas pendientes de OTROS usuarios no se pueden prestar, y
     * las reservas pendientes del usuario cuentan dentro de su límite de préstamos.
     *
     * @return array{ok: bool, error: ?string, id_prestamo: ?int}
     */
    public function crear(int $idLibro, int $idUsuario, ?int $idReserva = null): array
    {
        $this->db->begin_transaction();
        try {
            // Bloquea la fila del libro y lee su stock: cualquier otro pedido sobre este
            // mismo libro tiene que esperar a que esta transacción termine (commit o rollback).
            $stmt = $this->db->prepare("SELECT stock FROM libro WHERE id_libro = ? FOR UPDATE");
            $stmt->bind_param('i', $idLibro);
            $stmt->execute();
            $fila = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$fila) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'El libro no existe.', 'id_prestamo' => null];
            }

            $estadoActivo = self::ESTADO_ACTIVO;

            // Con la fila bloqueada esta lectura es segura. El descuento del stock lo hace
            // el trigger prestamo_ai al insertar el préstamo (y la devolución lo repone).
            if ((int) $fila['stock'] < 1) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'No hay stock disponible de ese libro.', 'id_prestamo' => null];
            }

            // Los ejemplares apartados por reservas pendientes (vigentes) de otros usuarios no se prestan.
            $horas     = self::HORAS_RESERVA;
            $pendiente = self::RESERVA_PENDIENTE;
            $excluir   = $idReserva ?? 0;
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) AS total FROM reserva
                 WHERE id_libro = ? AND id_estado = ? AND id_reserva <> ?
                   AND fecha_solicitud > DATE_SUB(NOW(), INTERVAL ? HOUR)"
            );
            $stmt->bind_param('iiii', $idLibro, $pendiente, $excluir, $horas);
            $stmt->execute();
            $reservadas = (int) $stmt->get_result()->fetch_assoc()['total'];
            $stmt->close();
            if ((int) $fila['stock'] - $reservadas < 1) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'Los ejemplares que quedan están reservados por otros usuarios.', 'id_prestamo' => null];
            }

            // Bloquea también la fila del usuario, para que dos préstamos simultáneos
            // del mismo alumno no burlen el límite de 3 contando "al mismo tiempo".
            $stmt = $this->db->prepare("SELECT id_perfil FROM usuario WHERE id_usuario = ? FOR UPDATE");
            $stmt->bind_param('i', $idUsuario);
            $stmt->execute();
            $filaUsuario = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$filaUsuario) {
                $this->db->rollback();
                return ['ok' => false, 'error' => 'El usuario no existe.', 'id_prestamo' => null];
            }

            // Entrega de una reserva: tiene que seguir pendiente y vigente, y ser de este usuario y libro.
            if ($idReserva !== null) {
                $stmt = $this->db->prepare(
                    "SELECT id_usuario, id_libro, id_estado,
                            (fecha_solicitud > DATE_SUB(NOW(), INTERVAL ? HOUR)) AS vigente
                     FROM reserva WHERE id_reserva = ? FOR UPDATE"
                );
                $stmt->bind_param('ii', $horas, $idReserva);
                $stmt->execute();
                $r = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                if (!$r || (int) $r['id_usuario'] !== $idUsuario || (int) $r['id_libro'] !== $idLibro
                    || (int) $r['id_estado'] !== $pendiente || !(int) $r['vigente']) {
                    $this->db->rollback();
                    return ['ok' => false, 'error' => 'La reserva ya no está pendiente o venció. Si corresponde, registrá un préstamo nuevo.', 'id_prestamo' => null];
                }
            }

            // Sanción vigente (multa): mientras dure, no puede pedir libros, sea cual sea su rol.
            $sancionHasta = (new MultaRepository())->sancionVigenteHasta($idUsuario);
            if ($sancionHasta !== null) {
                $this->db->rollback();
                return [
                    'ok'          => false,
                    'error'       => 'El usuario tiene una sanción vigente hasta el '
                                     . date('d/m/Y H:i', strtotime($sancionHasta)) . ' y no puede pedir libros.',
                    'id_prestamo' => null,
                ];
            }

            // El perfil se lee SIN bloqueo (solo se bloquea la fila del usuario), para no
            // serializar los préstamos de todos los alumnos sobre la misma fila de perfil.
            // Se toma de la BD y no de la sesión porque registrar() presta a nombre de otro usuario.
            $stmt = $this->db->prepare("SELECT tipo_perfil FROM perfil WHERE id_perfil = ?");
            $stmt->bind_param('i', $filaUsuario['id_perfil']);
            $stmt->execute();
            $filaPerfil = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $tipoPerfil = $filaPerfil['tipo_perfil'] ?? '';

            $limite = self::limitePorPerfil($tipoPerfil);

            // Perfil sin límite (profesor): no hace falta contar los préstamos activos.
            if ($limite !== null) {
                // Cuentan los préstamos activos y las reservas pendientes (menos la que se está entregando).
                $stmt = $this->db->prepare(
                    "SELECT (SELECT COUNT(*) FROM prestamo WHERE id_usuario = ? AND id_estado = ?)
                          + (SELECT COUNT(*) FROM reserva
                             WHERE id_usuario = ? AND id_estado = ? AND id_reserva <> ?
                               AND fecha_solicitud > DATE_SUB(NOW(), INTERVAL ? HOUR)) AS total"
                );
                $stmt->bind_param('iiiiii', $idUsuario, $estadoActivo, $idUsuario, $pendiente, $excluir, $horas);
                $stmt->execute();
                $total = (int) $stmt->get_result()->fetch_assoc()['total'];
                $stmt->close();
                if ($total >= $limite) {
                    $this->db->rollback();
                    return [
                        'ok'          => false,
                        'error'       => 'Alcanzaste el límite de ' . $limite . ' entre préstamos activos y reservas pendientes.',
                        'id_prestamo' => null,
                    ];
                }
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

            // Si se entrega una reserva, queda cumplida en la misma transacción.
            if ($idReserva !== null) {
                $cumplida = self::RESERVA_CUMPLIDA;
                $stmt = $this->db->prepare("UPDATE reserva SET id_estado = ? WHERE id_reserva = ? AND id_estado = ?");
                $stmt->bind_param('iii', $cumplida, $idReserva, $pendiente);
                $stmt->execute();
                $actualizada = $stmt->affected_rows === 1;
                $stmt->close();
                if (!$actualizada) {
                    $this->db->rollback();
                    return ['ok' => false, 'error' => 'No se pudo cumplir la reserva.', 'id_prestamo' => null];
                }
            }

            $this->db->commit();
            return ['ok' => true, 'error' => null, 'id_prestamo' => $idPrestamo];
        } catch (Throwable $e) {
            $this->db->rollback();
            error_log('Error PrestamoRepository::crear: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Ocurrió un error al registrar el préstamo.', 'id_prestamo' => null];
        }
    }

    /** Marca un préstamo activo (o vencido) como devuelto. */
    public function registrarDevolucion(int $idPrestamo): bool
    {
        $estadoDevuelto = self::ESTADO_DEVUELTO;
        $estadoActivo   = self::ESTADO_ACTIVO;
        $estadoVencido  = self::ESTADO_VENCIDO;
        $stmt = $this->db->prepare(
            "UPDATE prestamo
             SET fecha_devolucion = NOW(), id_estado = ?
             WHERE id_prestamo = ? AND id_estado IN (?, ?)"
        );
        $stmt->bind_param('iiii', $estadoDevuelto, $idPrestamo, $estadoActivo, $estadoVencido);
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