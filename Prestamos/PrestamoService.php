<?php
require_once dirname(__DIR__) . '/Core/Database.php';
require_once __DIR__ . '/PrestamoRepository.php';
require_once dirname(__DIR__) . '/Multas/MultaService.php';

/**
 * PrestamoService — lógica de negocio para préstamos.
 */
class PrestamoService
{
    private PrestamoRepository $repo;
    private MultaService       $multaService;

    public function __construct()
    {
        $this->repo         = new PrestamoRepository();
        $this->multaService = new MultaService();
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
     * Registra la devolución de un préstamo y genera, en la misma transacción, las multas
     * que correspondan: por retraso y/o por devolver el libro en mal estado.
     * Si algo falla, no queda ni la devolución ni las multas.
     *
     * @param string $estadoLibro 'bueno' | 'rayado' | 'maltratado' | 'roto'
     * @return array{ok:bool, error:?string, multas:array, prestamo:?array}
     */
    public function devolverPrestamo(int $idPrestamo, string $estadoLibro = 'bueno', string $observaciones = '', ?int $idRegistrador = null): array
    {
        $db            = Database::getConexion();
        $estadoLibro   = MultaService::normalizarEstadoLibro($estadoLibro);
        $observaciones = trim($observaciones);
        // Máximo 500 caracteres (límite de la columna), sin cortar a la mitad un carácter UTF-8
        if (preg_match('/^.{0,500}/su', $observaciones, $corte)) {
            $observaciones = $corte[0];
        }

        $db->begin_transaction();
        try {
            if (!$this->repo->registrarDevolucion($idPrestamo)) {
                $db->rollback();
                return [
                    'ok'       => false,
                    'error'    => 'No se pudo registrar la devolución (verificá que el préstamo exista y siga activo).',
                    'multas'   => [],
                    'prestamo' => null,
                ];
            }

            $prestamo = $this->repo->obtenerPorId($idPrestamo); // ya con fecha_devolucion
            $multas   = $this->multaService->generarPorDevolucion($prestamo, $estadoLibro, $observaciones, $idRegistrador);

            $db->commit();
            return ['ok' => true, 'error' => null, 'multas' => $multas, 'prestamo' => $prestamo];
        } catch (Throwable $e) {
            $db->rollback();
            error_log('Error PrestamoService::devolverPrestamo: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Ocurrió un error al registrar la devolución.', 'multas' => [], 'prestamo' => null];
        }
    }
}