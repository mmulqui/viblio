<?php
require_once __DIR__ . '/MultaRepository.php';

/**
 * MultaService — reglas de negocio de las multas.
 *
 * Toda la política de sanciones está en las constantes de abajo: para cambiar
 * cuántos días dura una sanción, se cambia solo acá.
 */
class MultaService
{
    /** Estados en los que puede volver un libro. */
    public const ESTADOS_LIBRO = ['bueno', 'rayado', 'maltratado', 'roto'];

    /** Días de sanción por devolución en mal estado. */
    public const DIAS_POR_DANIO = [
        MultaRepository::MOTIVO_RAYADO     => 7,
        MultaRepository::MOTIVO_MALTRATADO => 15,
        MultaRepository::MOTIVO_ROTO       => 30,
    ];

    /** Días de sanción por cada día de retraso en la devolución. */
    public const DIAS_SANCION_POR_DIA_DE_RETRASO = 1;

    private MultaRepository $repo;

    public function __construct()
    {
        $this->repo = new MultaRepository();
    }

    /** Devuelve un estado de libro válido ('bueno' si llega algo desconocido). */
    public static function normalizarEstadoLibro(string $estado): string
    {
        return in_array($estado, self::ESTADOS_LIBRO, true) ? $estado : 'bueno';
    }

    /**
     * Días de retraso, contando días de calendario: devolver el mismo día del
     * vencimiento no es retraso; al día siguiente, 1 día.
     */
    public static function diasDeRetraso(string $fechaVencimiento, string $fechaDevolucion): int
    {
        $vence    = new DateTime(substr($fechaVencimiento, 0, 10));
        $devuelto = new DateTime(substr($fechaDevolucion, 0, 10));
        return $devuelto > $vence ? (int) $vence->diff($devuelto)->days : 0;
    }

    /**
     * Calcula qué multas corresponden a una devolución (sin guardar nada).
     *
     * @param array $prestamo Debe traer fecha_vencimieto y fecha_devolucion.
     * @return array<int, array{motivo:string, dias:int, observaciones:string}>
     */
    public static function calcular(array $prestamo, string $estadoLibro, string $observaciones = ''): array
    {
        $multas = [];

        $retraso = self::diasDeRetraso($prestamo['fecha_vencimieto'], $prestamo['fecha_devolucion']);
        if ($retraso > 0) {
            $multas[] = [
                'motivo'        => MultaRepository::MOTIVO_RETRASO,
                'dias'          => $retraso * self::DIAS_SANCION_POR_DIA_DE_RETRASO,
                'observaciones' => "Devolución con $retraso día(s) de retraso (vencía el "
                                   . date('d/m/Y', strtotime($prestamo['fecha_vencimieto'])) . ').',
            ];
        }

        if (isset(self::DIAS_POR_DANIO[$estadoLibro])) {
            $multas[] = [
                'motivo'        => $estadoLibro,
                'dias'          => self::DIAS_POR_DANIO[$estadoLibro],
                'observaciones' => $observaciones !== '' ? $observaciones : "Libro devuelto en estado: $estadoLibro.",
            ];
        }

        return $multas;
    }

    /**
     * Genera y guarda las multas de una devolución ya registrada.
     * No abre transacción: PrestamoService la maneja junto con la devolución.
     *
     * @param array $prestamo Fila del préstamo ya con fecha_devolucion.
     * @return array<int, array{id_multa:int, motivo:string, dias:int}>
     */
    public function generarPorDevolucion(array $prestamo, string $estadoLibro, string $observaciones, ?int $idRegistrador): array
    {
        $creadas = [];
        foreach (self::calcular($prestamo, $estadoLibro, $observaciones) as $m) {
            $idMulta = $this->repo->crear(
                (int) $prestamo['id_usuario'],
                (int) $prestamo['id_prestamo'],
                $m['motivo'],
                $m['dias'],
                $m['observaciones'],
                $idRegistrador
            );
            $creadas[] = ['id_multa' => $idMulta, 'motivo' => $m['motivo'], 'dias' => $m['dias']];
        }

        if ($creadas) {
            $this->repo->recalcularContador((int) $prestamo['id_usuario']);
        }
        return $creadas;
    }
}