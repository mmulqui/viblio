<?php
require_once dirname(__DIR__) . '/Core/Database.php';

/**
 * IngresoStockRepository — historial de ingresos de stock (tabla ingreso_stock).
 * Cada "Agregar stock" deja una fila; si fue una compra puede llevar la imagen de la boleta.
 */
class IngresoStockRepository
{
    /** Motivos posibles del ingreso => texto para mostrar. */
    public const MOTIVOS = [
        'compra'   => 'Compra',
        'donacion' => 'Donación',
        'otro'     => 'Otro',
    ];

    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    /** true si la tabla ingreso_stock existe (si no, hay que ejecutar ingreso_stock.sql). */
    public static function disponible(): bool
    {
        static $existe = null;
        if ($existe === null) {
            try {
                $res = Database::getConexion()->query(
                    "SELECT 1 FROM information_schema.TABLES
                     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ingreso_stock' LIMIT 1"
                );
                $existe = $res && $res->num_rows > 0;
            } catch (Throwable $e) {
                $existe = false;
            }
        }
        return $existe;
    }

    /**
     * Guarda un ingreso. No abre transacción: la maneja LibroRepository::agregarStock,
     * para que el stock y su registro se guarden juntos o no se guarden.
     *
     * @param array{id_libro:int, titulo:string, isbn:string} $libro
     * @return int id del ingreso creado
     */
    public function registrar(
        array $libro,
        int $cantidad,
        int $stockAnterior,
        int $stockNuevo,
        string $motivo,
        ?string $procedencia,
        ?string $boleta,
        ?string $boletaTipo,
        ?int $idRegistrador
    ): int {
        $stmt = $this->db->prepare(
            "INSERT INTO ingreso_stock
                (id_libro, titulo_libro, isbn, cantidad, stock_anterior, stock_nuevo,
                 motivo, procedencia, boleta, boleta_tipo, id_registrador)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $idLibro = (int) $libro['id_libro'];
        $titulo  = $libro['titulo'];
        $isbn    = $libro['isbn'];
        $tipoBoleta = $boleta === null ? 's' : 'b'; // 'b' = dato grande: se envía aparte con send_long_data
        $sinDatos   = null;

        $stmt->bind_param(
            'issiiis' . 's' . $tipoBoleta . 'si',
            $idLibro, $titulo, $isbn, $cantidad, $stockAnterior, $stockNuevo,
            $motivo, $procedencia, $sinDatos, $boletaTipo, $idRegistrador
        );
        if ($boleta !== null) {
            $stmt->send_long_data(8, $boleta);
        }
        $stmt->execute();
        $idIngreso = $stmt->insert_id;
        $stmt->close();

        return $idIngreso;
    }

    /**
     * Lista los ingresos para la pestaña Ingresos (sin traer las imágenes).
     *
     * @param string $busqueda Busca en título, ISBN y procedencia.
     * @param string $motivo   'compra' | 'donacion' | 'otro' | '' (todos).
     * @param string $desde    Fecha inicial YYYY-MM-DD (inclusive); inválida o vacía = sin límite.
     * @param string $hasta    Fecha final YYYY-MM-DD (inclusive); inválida o vacía = sin límite.
     */
    public function listar(string $busqueda = '', string $motivo = '', string $desde = '', string $hasta = ''): array
    {
        $where  = [];
        $tipos  = '';
        $params = [];

        if ($busqueda !== '') {
            $where[] = '(i.titulo_libro LIKE ? OR i.isbn LIKE ? OR i.procedencia LIKE ?)';
            $tipos  .= 'sss';
            $like    = "%$busqueda%";
            array_push($params, $like, $like, $like);
        }
        if (isset(self::MOTIVOS[$motivo])) {
            $where[]  = 'i.motivo = ?';
            $tipos   .= 's';
            $params[] = $motivo;
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde)) {
            $where[]  = 'i.fecha >= ?';
            $tipos   .= 's';
            $params[] = "$desde 00:00:00";
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta)) {
            $where[]  = 'i.fecha <= ?';
            $tipos   .= 's';
            $params[] = "$hasta 23:59:59";
        }

        $sql = "SELECT i.id_ingreso, i.fecha, i.titulo_libro, i.isbn, i.cantidad,
                       i.stock_anterior, i.stock_nuevo, i.motivo, i.procedencia,
                       (i.boleta IS NOT NULL) AS tiene_boleta,
                       p.nombre, p.apellido
                FROM ingreso_stock i
                LEFT JOIN usuario u ON u.id_usuario = i.id_registrador
                LEFT JOIN persona p ON p.id_persona = u.persona_id_persona"
             . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
             . " ORDER BY i.fecha DESC, i.id_ingreso DESC";

        $stmt = $this->db->prepare($sql);
        if ($params) {
            $stmt->bind_param($tipos, ...$params);
        }
        $stmt->execute();
        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $filas;
    }

    /** Imagen de la boleta de un ingreso: ['boleta' => bytes, 'boleta_tipo' => mime], o null si no tiene. */
    public function obtenerBoleta(int $idIngreso): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT boleta, boleta_tipo FROM ingreso_stock WHERE id_ingreso = ? AND boleta IS NOT NULL"
        );
        $stmt->bind_param('i', $idIngreso);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila ?: null;
    }
}