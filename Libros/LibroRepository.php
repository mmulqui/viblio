<?php
require_once dirname(__DIR__) . '/Core/Database.php';


class LibroRepository
{
    /** Máximo de ejemplares que se pueden cargar de una vez con "Agregar stock" (evita errores de tipeo). */
    public const MAX_STOCK_POR_CARGA = 9999;

    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    private function sqlBase(): string
    {
        return "SELECT l.id_libro, l.isbn, l.titulo, l.edicion,
                       l.anio_publicacion, l.estado, l.stock,
                       a.nombre  AS autor,
                       ed.nombre AS editorial,
                       c.nombre  AS categoria,
                       g.nombre  AS genero
                FROM libro l
                LEFT JOIN rela_aut_lib     ral ON l.id_libro = ral.id_libro
                LEFT JOIN autor            a   ON ral.id_autor = a.id_autor
                LEFT JOIN rela_edit_lib    rel ON l.id_libro = rel.id_libro
                LEFT JOIN editorial        ed  ON rel.id_editorial = ed.id_editorial
                LEFT JOIN rela_cat_lib_gen rcg ON l.id_libro = rcg.id_libro
                LEFT JOIN categoria        c   ON rcg.id_categoria = c.id_categoria
                LEFT JOIN genero           g   ON rcg.id_genero = g.id_genero";
    }

    /** Lista libros con búsqueda opcional.*/
    public function listar(string $busqueda = '', bool $todos = false): array
    {
        $sql = $this->sqlBase();

        if ($todos || $busqueda === '') {
            return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        }

        $stmt = $this->db->prepare(
            "$sql WHERE l.titulo     LIKE ?
                    OR  a.nombre     LIKE ?
                    OR  ed.nombre    LIKE ?
                    OR  c.nombre     LIKE ?
                    OR  g.nombre     LIKE ?
                    OR  l.isbn       LIKE ?"
        );
        $p = "%$busqueda%";
        $stmt->bind_param('ssssss', $p, $p, $p, $p, $p, $p);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }

    /** Lista libros activos para alumnos. */
    public function listarActivos(string $busqueda = '', bool $todos = false): array
    {
        $sql = $this->sqlBase() . " WHERE l.activo = 1";

        if ($todos || $busqueda === '') {
            return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        }

        $stmt = $this->db->prepare(
            "$sql AND (l.titulo  LIKE ?
                    OR a.nombre  LIKE ?
                    OR ed.nombre LIKE ?
                    OR c.nombre  LIKE ?
                    OR g.nombre  LIKE ?
                    OR l.isbn    LIKE ?)"
        );
        $p = "%$busqueda%";
        $stmt->bind_param('ssssss', $p, $p, $p, $p, $p, $p);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }

    /** Obtiene los datos de un libro por su ISBN. */
    public function obtenerPorIsbn(string $isbn): ?array
    {
        $stmt = $this->db->prepare($this->sqlBase() . " WHERE l.isbn = ? LIMIT 1");
        $stmt->bind_param('s', $isbn);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila ?: null;
    }

    /** Registra un nuevo libro vía stored procedure. */
    public function registrar(array $datos): bool
    {
        $stmt = $this->db->prepare(
            "CALL registrar_libro(?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stock = max(0, (int) ($datos['stock'] ?? 0));
        $stmt->bind_param('ssssissss',
            $datos['titulo'],
            $datos['edicion'],
            $datos['anio'],
            $datos['isbn'],
            $stock,
            $datos['autor'],
            $datos['editorial'],
            $datos['categoria'],
            $datos['genero']
        );
        $ok = $stmt->execute();
        if (!$ok) error_log("Error registrar_libro: " . $stmt->error);
        $stmt->close();
        return $ok;
    }

    /**
     * Modifica los datos de un libro vía stored procedure. NO cambia el stock: eso se hace
     * con agregarStock(). Como el procedimiento recibe el stock, se le pasa el actual, leído
     * con bloqueo de fila para no pisar un préstamo o devolución que ocurra en ese instante.
     */
    public function modificar(array $datos): bool
    {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("SELECT stock FROM libro WHERE isbn = ? FOR UPDATE");
            $stmt->bind_param('s', $datos['isbn']);
            $stmt->execute();
            $fila = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$fila) {
                $this->db->rollback();
                return false;
            }
            $stock = (int) $fila['stock'];

            $stmt = $this->db->prepare(
                "CALL modificar_libro(?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('ssssissss',
                $datos['isbn'],
                $datos['titulo'],
                $datos['edicion'],
                $datos['anio'],
                $stock,
                $datos['autor'],
                $datos['editorial'],
                $datos['categoria'],
                $datos['genero']
            );
            $ok = $stmt->execute();
            if (!$ok) error_log("Error modificar_libro: " . $stmt->error);
            $stmt->close();

            $ok ? $this->db->commit() : $this->db->rollback();
            return $ok;
        } catch (Throwable $e) {
            $this->db->rollback();
            error_log('Error modificar_libro: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Suma ejemplares al stock de un libro (ingreso de libros: compra, donación, etc.).
     * El trigger libro_bu recalcula "estado" a partir del stock, así que un libro sin
     * stock vuelve a quedar disponible.
     *
     * @return array{titulo:string, isbn:string, stock_anterior:int, stock_nuevo:int}|null
     *         null si el libro no existe o no se pudo guardar.
     */
    public function agregarStock(int $idLibro, int $cantidad): ?array
    {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("SELECT titulo, isbn, stock FROM libro WHERE id_libro = ? FOR UPDATE");
            $stmt->bind_param('i', $idLibro);
            $stmt->execute();
            $libro = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$libro) {
                $this->db->rollback();
                return null;
            }

            $stmt = $this->db->prepare("UPDATE libro SET stock = stock + ? WHERE id_libro = ?");
            $stmt->bind_param('ii', $cantidad, $idLibro);
            $stmt->execute();
            $stmt->close();

            $this->db->commit();
            return [
                'titulo'         => $libro['titulo'],
                'isbn'           => $libro['isbn'],
                'stock_anterior' => (int) $libro['stock'],
                'stock_nuevo'    => (int) $libro['stock'] + $cantidad,
            ];
        } catch (Throwable $e) {
            $this->db->rollback();
            error_log('Error agregarStock: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Elimina un libro y sus relaciones (en transacción).
     */
    public function eliminar(string $isbn): bool
    {
        // Obtener id_libro
        $stmt = $this->db->prepare("SELECT id_libro FROM libro WHERE isbn = ?");
        $stmt->bind_param('s', $isbn);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$fila) return false;
        $idLibro = (int) $fila['id_libro'];

        $this->db->begin_transaction();
        try {
            foreach (['rela_aut_lib', 'rela_edit_lib', 'rela_cat_lib_gen'] as $tabla) {
                $s = $this->db->prepare("DELETE FROM $tabla WHERE id_libro = ?");
                $s->bind_param('i', $idLibro);
                $s->execute();
                $s->close();
            }
            $s = $this->db->prepare("DELETE FROM libro WHERE isbn = ?");
            $s->bind_param('s', $isbn);
            $s->execute();
            $s->close();
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollback();
            error_log("Error eliminar_libro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Resta 1 al stock de un libro. Devuelve false si no hay stock.
     * La condición "stock > 0" evita que dos préstamos simultáneos se lleven el último ejemplar.
     */
    public function descontarStock(int $idLibro): bool
    {
        $stmt = $this->db->prepare("UPDATE libro SET stock = stock - 1 WHERE id_libro = ? AND stock > 0");
        $stmt->bind_param('i', $idLibro);
        $stmt->execute();
        $ok = $stmt->affected_rows === 1;
        $stmt->close();
        return $ok;
    }

    /** Suma 1 al stock de un libro (por ejemplo, al devolverlo). */
    public function sumarStock(int $idLibro): bool
    {
        $stmt = $this->db->prepare("UPDATE libro SET stock = stock + 1 WHERE id_libro = ?");
        $stmt->bind_param('i', $idLibro);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}