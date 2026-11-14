<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Core/Auditoria.php';
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
        $this->auditar('registrar_reserva', $error, $idLibro, $idUsuario);
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
        $this->auditar('solicitar_reserva', $error, $idLibro, $idUsuario);
        $error
            ? $this->alerta('warning', 'Ups', $error)
            : $this->alerta('success', '¡Éxito!', 'Reserva registrada correctamente.');
    }

    /** Cancela una reserva del usuario logueado. */
    public function cancelar(): void
    {
        $idReserva = (int) ($_POST['id_reserva'] ?? $_GET['id_reserva'] ?? 0);

        // Alumnos y profesores solo pueden cancelar sus propias reservas.
        if (($_SESSION['rol'] ?? '') !== 'bibliotecario' && !$this->esDelUsuario($idReserva)) {
            Auditoria::log('Reservas', 'cancelar_reserva_rechazada', "reserva id: $idReserva | no pertenece al usuario");
            $this->alerta('warning', 'Ups', 'No podés cancelar esa reserva.');
        }

        $error = $this->service->cancelarReserva($idReserva);
        if ($error === null) {
            Auditoria::log('Reservas', 'cancelar_reserva', "reserva id: $idReserva");
        }
        $error
            ? $this->alerta('warning', 'Ups', $error)
            : $this->alerta('success', '¡Éxito!', 'Reserva cancelada correctamente.');
    }

    /** Audita un intento de reserva: éxito con $accion, o 'reserva_rechazada' con el motivo. */
    private function auditar(string $accion, ?string $error, int $idLibro, int $idUsuario): void
    {
        $detalle = 'libro: ' . Auditoria::describirLibro($idLibro)
                 . ' | usuario: ' . Auditoria::describirUsuario($idUsuario);
        $error === null
            ? Auditoria::log('Reservas', $accion, $detalle)
            : Auditoria::log('Reservas', 'reserva_rechazada', "$detalle | motivo: $error");
    }

    private function alerta(string $tipo, string $titulo, string $msg): void
    {
        $_SESSION['alerta'] = compact('tipo', 'titulo', 'msg');
        $this->redirigir();
    }

    private function redirigir(): never
    {
        if (($_SESSION['rol'] ?? '') === 'bibliotecario') {
            header('Location: ../views/menu.php');
        } else {
            header('Location: ../views/' . self::paginaDeRetorno());
        }
        exit;
    }

    /** true si la reserva pertenece al usuario logueado. */
    private function esDelUsuario(int $idReserva): bool
    {
        foreach ($this->repo->listarPorUsuario((int) $_SESSION['id_usuario']) as $r) {
            if ((int) $r['id_reserva'] === $idReserva) {
                return true;
            }
        }
        return false;
    }

    /** Página de usuario a la que se vuelve (llega en el form como "volver"; solo se aceptan estas). */
    private static function paginaDeRetorno(): string
    {
        $permitidas = ['catalogo.php', 'mis_prestamos.php', 'mis_reservas.php', 'menu_usuario.php'];
        $volver = (string) ($_POST['volver'] ?? '');
        return in_array($volver, $permitidas, true) ? $volver : 'catalogo.php';
    }
}