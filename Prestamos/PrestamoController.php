<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Core/Auditoria.php';
require_once dirname(__DIR__) . '/Prestamos/Prestamo.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoService.php';
require_once dirname(__DIR__) . '/Multas/MultaService.php';
require_once dirname(__DIR__) . '/Reservas/ReservaRepository.php';

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
            'entregar'  => $this->entregar(),
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
        $this->auditar('registrar_prestamo', $error, $idLibro, $idUsuario);
        $error
            ? $this->alerta('warning', 'Ups', $error)
            : $this->alerta('success', '¡Éxito!', 'Préstamo registrado correctamente.');
    }

    /**
     * Ya no se presta desde el catálogo: el préstamo nace cuando el bibliotecario entrega el
     * libro (ver entregar()). Esta acción solo avisa a quien la invoque.
     */
    public function solicitar(): void
    {
        $this->alerta('warning', 'Ups', 'Los préstamos los registra el bibliotecario cuando retirás el libro. Reservalo desde el catálogo.');
    }

    /**
     * El bibliotecario entrega el libro de una reserva pendiente: recién ahí nace el préstamo
     * (con su plazo) y la reserva queda cumplida. Se vuelven a validar stock, límite y sanción.
     */
    public function entregar(): void
    {
        AuthGuard::verificarRol(['bibliotecario']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir();
            return;
        }

        $idReserva = (int) ($_POST['id_reserva'] ?? 0);
        $reserva   = (new ReservaRepository())->obtenerPorId($idReserva);
        if (!$reserva) {
            $this->alerta('warning', 'Ups', 'La reserva no existe.', 'reservas');
        }

        $idLibro   = (int) $reserva['id_libro'];
        $idUsuario = (int) $reserva['id_usuario'];

        $error = $this->service->solicitarPrestamo($idLibro, $idUsuario, $idReserva);
        $this->auditar('entregar_reserva', $error, $idLibro, $idUsuario);
        $error
            ? $this->alerta('warning', 'Ups', $error, 'reservas')
            : $this->alerta('success', '¡Éxito!', 'Libro entregado: el préstamo quedó registrado y la reserva cumplida.', 'reservas');
    }

    /** Registra la devolución de un préstamo. Solo el bibliotecario puede hacerlo. */
    /**
     * Registra la devolución. Si fue tardía o el libro volvió en mal estado
     * (rayado, maltratado, roto) se generan las sanciones correspondientes.
     */
    public function devolver(): void
    {
        AuthGuard::verificarRol(['bibliotecario']);

        $idPrestamo    = (int) ($_POST['id_prestamo'] ?? $_GET['id_prestamo'] ?? 0);
        $estadoLibro   = MultaService::normalizarEstadoLibro((string) ($_POST['estado_libro'] ?? 'bueno'));
        $observaciones = (string) ($_POST['observaciones'] ?? '');

        $resultado = $this->service->devolverPrestamo($idPrestamo, $estadoLibro, $observaciones, (int) $_SESSION['id_usuario']);
        if (!$resultado['ok']) {
            $this->alerta('warning', 'Ups', $resultado['error'], 'prestamos');
        }

        $prestamo  = $resultado['prestamo'];
        $idUsuario = (int) $prestamo['id_usuario'];
        $usuario   = Auditoria::describirUsuario($idUsuario);

        Auditoria::log(
            'Préstamos',
            'devolver_prestamo',
            "préstamo id: $idPrestamo | libro: " . Auditoria::describirLibro((int) $prestamo['id_libro'])
                . " | usuario: $usuario | estado del libro: $estadoLibro"
        );
        foreach ($resultado['multas'] as $m) {
            Auditoria::log(
                'Multas',
                'generar_multa',
                "multa id: {$m['id_multa']} | usuario: $usuario | motivo: {$m['motivo']} | {$m['dias']} día(s) | préstamo id: $idPrestamo"
            );
        }

        if ($resultado['multas']) {
            $detalle = implode(', ', array_map(fn($m) => "{$m['motivo']} ({$m['dias']} día(s))", $resultado['multas']));
            $hasta   = (new MultaRepository())->sancionVigenteHasta($idUsuario);
            $msg     = "Devolución registrada. Se generó una sanción por: $detalle."
                     . ($hasta ? ' El usuario no podrá pedir libros ni reservar hasta el ' . date('d/m/Y H:i', strtotime($hasta)) . '.' : '');
            $this->alerta('warning', 'Devolución con sanción', $msg, 'prestamos');
        }

        $this->alerta('success', '¡Éxito!', 'Devolución registrada correctamente.', 'prestamos');
    }

    /** Audita un intento de préstamo: éxito con $accion, o 'prestamo_rechazado' con el motivo. */
    private function auditar(string $accion, ?string $error, int $idLibro, int $idUsuario): void
    {
        $detalle = 'libro: ' . Auditoria::describirLibro($idLibro)
                 . ' | usuario: ' . Auditoria::describirUsuario($idUsuario);
        $error === null
            ? Auditoria::log('Préstamos', $accion, $detalle)
            : Auditoria::log('Préstamos', 'prestamo_rechazado', "$detalle | motivo: $error");
    }

    private function alerta(string $tipo, string $titulo, string $msg, string $tab = ''): void
    {
        $_SESSION['alerta'] = compact('tipo', 'titulo', 'msg');
        $this->redirigir($tab);
    }

    /** $tab = pestaña de menu.php que se reabre al volver (vacío = la de inicio). */
    private function redirigir(string $tab = ''): never
    {
        if (($_SESSION['rol'] ?? '') === 'bibliotecario') {
            header('Location: ../views/menu.php' . ($tab !== '' ? '?tab=' . urlencode($tab) : ''));
        } else {
            header('Location: ../views/' . self::paginaDeRetorno());
        }
        exit;
    }

    /** Página de usuario a la que se vuelve (llega en el form como "volver"; solo se aceptan estas). */
    private static function paginaDeRetorno(): string
    {
        $permitidas = ['catalogo.php', 'mis_prestamos.php', 'mis_reservas.php', 'menu_usuario.php'];
        $volver = (string) ($_POST['volver'] ?? '');
        return in_array($volver, $permitidas, true) ? $volver : 'catalogo.php';
    }
}