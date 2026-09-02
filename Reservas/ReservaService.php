<?php
require_once __DIR__ . '/ReservaRepository.php';

/**
 * ReservaService — lógica de negocio para reservas.
 */
class ReservaService
{
    private ReservaRepository $repo;

    public function __construct()
    {
        $this->repo = new ReservaRepository();
    }

    /**
     * Intenta registrar una reserva. La validación de "no está prestado"
     * ocurre dentro de una transacción con bloqueo de fila en el repository.
     *
     * @return string|null Mensaje de error, o null si la reserva se registró correctamente.
     */
    public function solicitarReserva(int $idLibro, int $idUsuario): ?string
    {
        $resultado = $this->repo->crear($idLibro, $idUsuario);
        return $resultado['ok'] ? null : $resultado['error'];
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