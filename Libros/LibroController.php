<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Core/Auditoria.php';
require_once dirname(__DIR__) . '/Libros/Libro.php';
require_once dirname(__DIR__) . '/Libros/LibroRepository.php';

class LibroController
{
    private LibroRepository $repo;

    public function __construct()
    {
        AuthGuard::verificarRol(['bibliotecario']); // ← corrige: antes faltaba en modificar/obtener/cambiarEstado
        $this->repo = new LibroRepository();
    }

    public function registrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir();
        }

        $campos = ['titulo', 'edicion', 'anio', 'isbn', 'autor', 'editorial', 'categoria', 'genero'];
        foreach ($campos as $campo) {
            if (empty($_POST[$campo]) && $campo !== 'edicion') {
                $this->alerta('warning', 'Ups', 'Completá todos los campos obligatorios.');
                return;
            }
        }

        if (!isset($_POST['stock']) || !ctype_digit((string) $_POST['stock'])) {
            $this->alerta('warning', 'Ups', 'Ingresá un stock válido (0 o más).');
            return;
        }

        $ok = $this->repo->registrar($_POST);
        if ($ok) {
            Auditoria::log('Libros', 'registrar_libro', "{$_POST['titulo']} (ISBN {$_POST['isbn']}) | stock: {$_POST['stock']}");
        }
        $ok
            ? $this->alerta('success', '¡Éxito!', 'Libro agregado correctamente.')
            : $this->alerta('error', 'Error', 'No se pudo agregar el libro.');
    }

    public function modificar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir();
        }

        // El stock no se modifica acá: se maneja con "Agregar stock" (agregarStock()).
        $ok = $this->repo->modificar($_POST);
        if ($ok) {
            Auditoria::log('Libros', 'modificar_libro', "{$_POST['titulo']} (ISBN {$_POST['isbn']})");
        }
        $ok
            ? $this->alerta('success', '¡Éxito!', 'Libro modificado correctamente.')
            : $this->alerta('error', 'Error', 'No se pudo modificar el libro.');
    }

    /**
     * Suma ejemplares al stock de un libro. La procedencia (compra, donación, etc.)
     * es opcional y queda registrada en la auditoría.
     */
    public function agregarStock(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir();
        }

        $idLibro  = (int) ($_POST['id_libro'] ?? 0);
        $cantidad = (string) ($_POST['cantidad'] ?? '');

        $procedencia = trim((string) ($_POST['procedencia'] ?? ''));
        if (preg_match('/^.{0,255}/su', $procedencia, $corte)) { // máx. 255 caracteres, sin cortar UTF-8
            $procedencia = $corte[0];
        }

        if ($idLibro <= 0) {
            $this->alerta('warning', 'Ups', 'Elegí el libro al que querés agregar stock.');
            return;
        }
        if (!ctype_digit($cantidad) || (int) $cantidad < 1 || (int) $cantidad > LibroRepository::MAX_STOCK_POR_CARGA) {
            $this->alerta('warning', 'Ups', 'Ingresá una cantidad entre 1 y ' . LibroRepository::MAX_STOCK_POR_CARGA . '.');
            return;
        }
        $cantidad = (int) $cantidad;

        $r = $this->repo->agregarStock($idLibro, $cantidad);
        if ($r === null) {
            $this->alerta('error', 'Error', 'No se pudo agregar el stock (verificá que el libro exista).');
            return;
        }

        Auditoria::log(
            'Libros',
            'agregar_stock',
            "{$r['titulo']} (ISBN {$r['isbn']}) | +$cantidad ejemplar(es) (stock {$r['stock_anterior']} → {$r['stock_nuevo']})"
                . ($procedencia !== '' ? " | procedencia: $procedencia" : '')
        );
        $this->alerta('success', '¡Éxito!', "Se agregaron $cantidad ejemplar(es) a \"{$r['titulo']}\". Stock actual: {$r['stock_nuevo']}.");
    }

    public function eliminar(): void
    {
        $isbn = $_GET['isbn'] ?? '';
        if (!$isbn) {
            $this->alerta('warning', 'Ups', 'ISBN no proporcionado.');
            return;
        }
        $libro = $this->repo->obtenerPorIsbn($isbn); // antes de eliminar, para el detalle
        $ok = $this->repo->eliminar($isbn);
        if ($ok) {
            Auditoria::log('Libros', 'eliminar_libro', ($libro['titulo'] ?? 'título desconocido') . " (ISBN $isbn)");
        }
        $ok
            ? $this->alerta('success', '¡Éxito!', 'Libro eliminado correctamente.')
            : $this->alerta('warning', 'Ups', 'No se pudo eliminar el libro con ese ISBN.');
    }

    public function obtener(): void
    {
        header('Content-Type: application/json');
        $isbn = $_GET['isbn'] ?? '';
        if (!$isbn) {
            echo json_encode(['success' => false, 'message' => 'ISBN no proporcionado']);
            return;
        }
        $libro = $this->repo->obtenerPorIsbn($isbn);
        echo json_encode($libro
            ? ['success' => true,  'libro'   => $libro]
            : ['success' => false, 'message' => 'Libro no encontrado']
        );
    }

    // Helpers

    private function alerta(string $tipo, string $titulo, string $msg): void
    {
        $_SESSION['alerta'] = compact('tipo', 'titulo', 'msg');
        $this->redirigir();
    }

    private function redirigir(): never
    {
        header('Location: ../views/menu.php');
        exit;
    }
}