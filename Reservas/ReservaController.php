<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Reservas/Reserva.php';
require_once dirname(__DIR__) . '/Reservas/ReservaRepository.php';
require_once dirname(__DIR__) . '/Reservas/ReservaService.php';

class ReservaController
{
    private ReservaRepository $repo;
    private ReservaService    $service;

    public function __construct()
    {
        AuthGuard::verificarRol(['bibliotecario', 'alumno', 'profesor']);
        $this->repo    = new ReservaRepository();
        $this->service = new ReservaService();
    }

    /** Dispatcher: lee ?accion= o la ruta que llama directamente al método. */
    public function handle(): void
    {
        $accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
        match ($accion) {
            'listar'    => $this->listar(),
            'solicitar' => $this->solicitar(),
            'registrar' => $this->registrar(),
            'cancelar'  => $this->cancelar(),
            default     => $this->redirigir(),
        };
    }

    /** Lista las reservas del usuario logueado. */
    public function listar(): void
    {
        header('Content-Type: application/json');
        $idUsuario = (int) $_SESSION['id_usuario'];
        $reservas  = $this->repo->listarPorUsuario($idUsuario);
        echo json_encode(['success' => true, 'reservas' => $reservas]);
    }

    /**
     * El bibliotecario registra una reserva a nombre de un alumno/profesor.
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

        $error = $this->service->solicitarReserva($idLibro, $idUsuario);
        $error
            ? $this->alerta('warning', 'Ups', $error)
            : $this->alerta('success', '¡Éxito!', 'Reserva registrada correctamente.');
    }

    /** Solicita una reserva para el usuario logueado. */
    public function solicitar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir();
            return;
        }

        $idLibro   = (int) ($_POST['id_libro'] ?? 0);
        $idUsuario = (int) $_SESSION['id_usuario'];

        $error = $this->service->solicitarReserva($idLibro, $idUsuario);
        $error
            ? $this->alerta('warning', 'Ups', $error)
            : $this->alerta('success', '¡Éxito!', 'Reserva registrada correctamente.');
    }

    /** Cancela una reserva del usuario logueado. */
    public function cancelar(): void
    {
        $idReserva = (int) ($_POST['id_reserva'] ?? $_GET['id_reserva'] ?? 0);
        $error = $this->service->cancelarReserva($idReserva);
        $error
            ? $this->alerta('warning', 'Ups', $error)
            : $this->alerta('success', '¡Éxito!', 'Reserva cancelada correctamente.');
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