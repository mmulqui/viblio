<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Libros/IngresoStockRepository.php';

/** IngresoStockController — muestra la imagen de la boleta de un ingreso de stock. */
class IngresoStockController
{
    private IngresoStockRepository $repo;

    public function __construct()
    {
        AuthGuard::verificarRol(['bibliotecario']);
        $this->repo = new IngresoStockRepository();
    }

    /** Envía la imagen directamente al navegador (solo para bibliotecarios con sesión). */
    public function verBoleta(): void
    {
        $idIngreso = (int) ($_GET['id'] ?? 0);
        $boleta    = $idIngreso > 0 ? $this->repo->obtenerBoleta($idIngreso) : null;

        if ($boleta === null) {
            http_response_code(404);
            echo 'Este ingreso no tiene boleta.';
            return;
        }

        // Solo se sirven tipos de imagen conocidos; nunca algo que el navegador pueda ejecutar.
        $tipo = in_array($boleta['boleta_tipo'], ['image/jpeg', 'image/png', 'image/webp'], true)
            ? $boleta['boleta_tipo']
            : 'application/octet-stream';

        header('Content-Type: ' . $tipo);
        header('Content-Length: ' . strlen($boleta['boleta']));
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, max-age=3600');
        header('Content-Disposition: inline; filename="boleta-ingreso-' . $idIngreso . '.jpg"');
        echo $boleta['boleta'];
    }
}