<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Prestamos/Prestamo.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoService.php';

class PrestamoController
{
    private PrestamoRepository $repo;
    private PrestamoService    $service;

    public function __construct()
    {
        AuthGuard::verificarRol(['bibliotecario', 'alumno', 'profesor']);
        $this->repo    = new PrestamoRepository();
        $this->service = new PrestamoService();
    }

    /** Dispatcher: lee ?accion= o la ruta que llama directamente al método. */
    public function handle(): void
    {
        $accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
        match ($accion) {
            'listar'    => $this->listar(),
            'solicitar' => $this->solicitar(),
            'registrar' => $this->registrar(),
            'devolver'  => $this->devolver(),
            default     => $this->redirigir(),
        };
    }

    /** Lista los préstamos del usuario logueado. */
    public function listar(): void
    {
        header('Content-Type: application/json');
        $idUsuario = (int) $_SESSION['id_usuario'];
        $prestamos = $this->repo->listarPorUsuario($idUsuario);
        echo json_encode(['success' => true, 'prestamos' => $prestamos]);
    }

    /**
     * El bibliotecario registra un préstamo a nombre de un alumno/profesor.
     * A diferencia de solicitar(), el id_usuario viene del formulario, no de la sesión.
     */
    public function registrar(): void
    {
        AuthGuard::verificarRol(['bibliotecario']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir();
            return;
        }

        $idLibro   = (int) ($_POST['id_libro'] ?? 0);
        $idUsuario = (int) ($_POST['id_usuario'] ?? 0);

        $error = $this->service->solicitarPrestamo($idLibro, $idUsuario);
        $error
            ? $this->alerta('warning', 'Ups', $error)
            : $this->alerta('success', '¡Éxito!', 'Préstamo registrado correctamente.');
    }

    /** Solicita un préstamo para el usuario logueado. */
    public function solicitar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir();
            return;
        }

        $idLibro   = (int) ($_POST['id_libro'] ?? 0);
        $idUsuario = (int) $_SESSION['id_usuario'];

        $error = $this->service->solicitarPrestamo($idLibro, $idUsuario);
        $error
            ? $this->alerta('warning', 'Ups', $error)
            : $this->alerta('success', '¡Éxito!', 'Préstamo registrado correctamente.');
    }

    /** Registra la devolución de un préstamo. Solo el bibliotecario puede hacerlo. */
    public function devolver(): void
    {
        AuthGuard::verificarRol(['bibliotecario']);

        $idPrestamo = (int) ($_POST['id_prestamo'] ?? $_GET['id_prestamo'] ?? 0);
        $error = $this->service->devolverPrestamo($idPrestamo);
        $error
            ? $this->alerta('warning', 'Ups', $error)
            : $this->alerta('success', '¡Éxito!', 'Devolución registrada correctamente.');
    }

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