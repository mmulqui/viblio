<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
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
        $ok
            ? $this->alerta('success', '¡Éxito!', 'Libro agregado correctamente.')
            : $this->alerta('error', 'Error', 'No se pudo agregar el libro.');
    }

    public function modificar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir();
        }

        if (!isset($_POST['stock']) || !ctype_digit((string) $_POST['stock'])) {
            $this->alerta('warning', 'Ups', 'Ingresá un stock válido (0 o más).');
            return;
        }

        $ok = $this->repo->modificar($_POST);
        $ok
            ? $this->alerta('success', '¡Éxito!', 'Libro modificado correctamente.')
            : $this->alerta('error', 'Error', 'No se pudo modificar el libro.');
    }

    public function eliminar(): void
    {
        $isbn = $_GET['isbn'] ?? '';
        if (!$isbn) {
            $this->alerta('warning', 'Ups', 'ISBN no proporcionado.');
            return;
        }
        $ok = $this->repo->eliminar($isbn);
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