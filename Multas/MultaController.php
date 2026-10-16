<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Core/Auditoria.php';
require_once dirname(__DIR__) . '/Multas/MultaRepository.php';

/**
 * MultaController — acciones sobre multas desde la pestaña Multas de menu.php.
 * Las multas se GENERAN al registrar la devolución (PrestamoController::devolver).
 */
class MultaController
{
    private MultaRepository $repo;

    public function __construct()
    {
        AuthGuard::verificarRol(['bibliotecario']);
        $this->repo = new MultaRepository();
    }

    public function handle(): void
    {
        $accion = $_POST['accion'] ?? '';
        match ($accion) {
            'anular' => $this->anular(),
            default  => $this->redirigir(),
        };
    }

    /** Anula una sanción (por ejemplo, si se cargó por error). */
    public function anular(): void
    {
        $idMulta = (int) ($_POST['id_multa'] ?? 0);
        $multa   = $this->repo->obtenerPorId($idMulta);

        if (!$multa || !$this->repo->anular($idMulta)) {
            $this->alerta('warning', 'Ups', 'No se pudo anular la sanción (verificá que exista y siga activa).');
        }

        $this->repo->recalcularContador((int) $multa['id_usuario']);
        Auditoria::log(
            'Multas',
            'anular_multa',
            "multa id: $idMulta | usuario: " . Auditoria::describirUsuario((int) $multa['id_usuario'])
                . " | motivo: {$multa['motivo']} | {$multa['dias_sancion']} día(s)"
        );
        $this->alerta('success', '¡Éxito!', 'Sanción anulada correctamente.');
    }

    private function alerta(string $tipo, string $titulo, string $msg): never
    {
        $_SESSION['alerta'] = compact('tipo', 'titulo', 'msg');
        $this->redirigir();
    }

    private function redirigir(): never
    {
        header('Location: ../views/menu.php?tab=multas');
        exit;
    }
}