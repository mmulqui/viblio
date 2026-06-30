<?php
require_once dirname(__DIR__) . '/Core/Database.php';

/**
 * LibroRepository — todas las consultas a la BD relacionadas con libros.
 *
 * Corrige los bugs del código original:
 *  - Usa prepared statements en lugar de interpolación directa (sin SQL injection)
 *  - Elimina el bug de `$titulo` faltante en procesar_libro.php
 *  - Agrega auth en todos los endpoints que la requerían
 */
class LibroRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // ─── Lectura ──────────────────────────────────────────────────────────────

    private function sqlBase(): string
    {
        return "SELECT l.id_libro, l.isbn, l.titulo, l.edicion,
                       l.anio_publicacion, l.estado,
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

    /** Lista libros con búsqueda opcional. Todos los parámetros como prepared. */
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

    // ─── Escritura ────────────────────────────────────────────────────────────

    /** Registra un nuevo libro vía stored procedure. */
    public function registrar(array $datos): bool
    {
        $stmt = $this->db->prepare(
            "CALL registrar_libro(?, ?, ?, ?, 1, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssssssss',
            $datos['titulo'],
            $datos['edicion'],
            $datos['anio'],
            $datos['isbn'],
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

    /** Modifica un libro vía stored procedure. */
    public function modificar(array $datos): bool
    {
        $stmt = $this->db->prepare(
            "CALL modificar_libro(?, ?, ?, ?, 1, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssssssss',
            $datos['isbn'],
            $datos['titulo'],
            $datos['edicion'],
            $datos['anio'],
            $datos['autor'],
            $datos['editorial'],
            $datos['categoria'],
            $datos['genero']
        );
        $ok = $stmt->execute();
        if (!$ok) error_log("Error modificar_libro: " . $stmt->error);
        $stmt->close();
        return $ok;
    }

    /**
     * Elimina un libro y sus relaciones (en transacción).
     * Corrige el código original que no usaba transacción.
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

    /** Cambia el estado (disponible/no disponible) de un libro. */
    public function cambiarEstado(string $isbn, int $estado): bool
    {
        if (!in_array($estado, [0, 1], true)) return false;
        $stmt = $this->db->prepare("UPDATE libro SET estado = ? WHERE isbn = ?");
        $stmt->bind_param('is', $estado, $isbn);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
