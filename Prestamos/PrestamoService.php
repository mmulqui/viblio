<?php
require_once __DIR__ . '/PrestamoRepository.php';

class PrestamoService
{
    private PrestamoRepository $repo;

    public function __construct()
    {
        $this->repo = new PrestamoRepository();
    }

    public function solicitarPrestamo(int $idLibro, int $idUsuario): ?string
    {
        if ($this->repo->libroEstaPrestado($idLibro)) {
            return 'ese libro ya esta prestado.';
        }

        if ($this->repo->contarActivosPorUsuario($idUsuario) >= PrestamoRepository::LIMITE_PRESTAMOS_ALUMNO) {
            return 'Alcanzaste el límite de ' . PrestamoRepository::LIMITE_PRESTAMOS_ALUMNO . ' préstamos simultáneos.';
        }
 
        $this->repo->crear($idLibro, $idUsuario);
        return null;
    }

    public function devolverPrestamo(int $idPrestamo): ?string
    {
        return $this->repo->registrarDevolucion($idPrestamo)
            ? null
            : 'No se pudo registrar la devolucion (verifica que el préstamo exista y siga activo).';
    }
}