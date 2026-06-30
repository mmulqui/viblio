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

    public function cambiarEstado(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $isbn   = $_POST['isbn']   ?? '';
        $estado = $_POST['estado'] ?? '';

        if (!$isbn || $estado === '') {
            echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
            return;
        }

        $ok = $this->repo->cambiarEstado($isbn, (int) $estado);
        echo json_encode($ok
            ? ['success' => true,  'message' => 'Estado actualizado correctamente']
            : ['success' => false, 'message' => 'No se pudo actualizar el estado']
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
