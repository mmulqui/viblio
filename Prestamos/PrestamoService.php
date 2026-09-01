<?php
require_once __DIR__ . '/PrestamoRepository.php';

/**
 * PrestamoService — lógica de negocio para préstamos.
 */
class PrestamoService
{
    private PrestamoRepository $repo;

    public function __construct()
    {
        $this->repo = new PrestamoRepository();
    }

    /**
     * Intenta registrar un préstamo. La validación de disponibilidad y límite
     * ocurre dentro de una transacción con bloqueo de fila en el repository.
     *
     * @return string|null Mensaje de error, o null si el préstamo se registró correctamente.
     */
    public function solicitarPrestamo(int $idLibro, int $idUsuario): ?string
    {
        $resultado = $this->repo->crear($idLibro, $idUsuario);
        return $resultado['ok'] ? null : $resultado['error'];
    }

    /**
     * Registra la devolución de un préstamo.
     *
     * @return string|null Mensaje de error, o null si la devolución se registró correctamente.
     */
    public function devolverPrestamo(int $idPrestamo): ?string
    {
        return $this->repo->registrarDevolucion($idPrestamo)
            ? null
            : 'No se pudo registrar la devolución (verificá que el préstamo exista y siga activo).';
    }
}