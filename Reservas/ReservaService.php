<?php
require_once __DIR__ . '/ReservaRepository.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';

/**
 * ReservaService — lógica de negocio para reservas.
 */
class ReservaService
{
    private ReservaRepository  $repo;
    private PrestamoRepository $prestamoRepo;

    public function __construct()
    {
        $this->repo         = new ReservaRepository();
        $this->prestamoRepo = new PrestamoRepository();
    }

    /**
     * Intenta registrar una reserva. Regla de negocio: un libro que ya está
     * prestado no se puede reservar.
     *
     * @return string|null Mensaje de error, o null si la reserva se registró correctamente.
     */
    public function solicitarReserva(int $idLibro, int $idUsuario): ?string
    {
        if ($this->prestamoRepo->libroEstaPrestado($idLibro)) {
            return 'No se puede reservar un libro que ya está prestado.';
        }

        $this->repo->crear($idLibro, $idUsuario);
        return null;
    }

    /**
     * @return string|null Mensaje de error, o null si la cancelación se registró correctamente.
     */
    public function cancelarReserva(int $idReserva): ?string
    {
        return $this->repo->cancelar($idReserva)
            ? null
            : 'No se pudo cancelar la reserva (verificá que exista y siga pendiente).';
    }
}