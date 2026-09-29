<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

/**
 * EstadisticaExportador — arma un .xlsx con gráficos NATIVOS de Excel
 * (editables, uno por hoja) a partir de los datos de EstadisticaRepository.
 */
class EstadisticaExportador
{
    private Spreadsheet $libro;

    public function __construct()
    {
        $this->libro = new Spreadsheet();
        $this->libro->removeSheetByIndex(0); // sacamos la hoja en blanco default
    }

    /** Genera el archivo completo y lo manda al navegador para descargar. */
    public function generarYDescargar(array $datos): void
    {
        $this->agregarHoja('Usuarios por rol',      $datos['usuariosPorRol']     ?? [], 'rol',    'cantidad', DataSeries::TYPE_BARCHART);
        $this->agregarHoja('Prestamos por estado',  $datos['prestamosPorEstado'] ?? [], 'estado', 'cantidad', DataSeries::TYPE_PIECHART);
        $this->agregarHoja('Prestamos por mes',     $datos['prestamosPorMes']    ?? [], 'mes',    'cantidad', DataSeries::TYPE_LINECHART);
        $this->agregarHoja('Libros mas prestados',  $datos['topLibros']          ?? [], 'titulo', 'cantidad', DataSeries::TYPE_BARCHART);
        $this->agregarHoja('Reservas por estado',   $datos['reservasPorEstado']  ?? [], 'estado', 'cantidad', DataSeries::TYPE_PIECHART);

        $this->libro->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="estadisticas_viblio.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($this->libro);
        $writer->setIncludeCharts(true); // clave: sin esto no exporta los gráficos
        $writer->save('php://output');
        exit;
    }

    private function agregarHoja(string $nombre, array $filas, string $campoEtiqueta, string $campoValor, string $tipoGrafico): void
    {
        $hoja = $this->libro->createSheet();
        $hoja->setTitle($this->normalizarNombreHoja($nombre));

        $hoja->setCellValue('A1', ucfirst($campoEtiqueta));
        $hoja->setCellValue('B1', 'Cantidad');

        $fila = 2;
        foreach ($filas as $f) {
            $hoja->setCellValue("A$fila", $f[$campoEtiqueta] ?? '');
            $hoja->setCellValue("B$fila", (int) ($f[$campoValor] ?? 0));
            $fila++;
        }
        $ultimaFila = $fila - 1;

        $hoja->getColumnDimension('A')->setAutoSize(true);
        $hoja->getColumnDimension('B')->setAutoSize(true);

        if ($ultimaFila < 2) {
            return; // no hay datos: no se puede armar un gráfico sin filas
        }

        $sheetName = $hoja->getTitle();
        $cantidad  = $ultimaFila - 1;

        $etiquetas = new DataSeriesValues(
            DataSeriesValues::DATASERIES_TYPE_STRING,
            "'$sheetName'!\$A\$2:\$A\$$ultimaFila",
            null,
            $cantidad
        );
        $valores = new DataSeriesValues(
            DataSeriesValues::DATASERIES_TYPE_NUMBER,
            "'$sheetName'!\$B\$2:\$B\$$ultimaFila",
            null,
            $cantidad
        );

        $series = new DataSeries(
            $tipoGrafico,
            $tipoGrafico === DataSeries::TYPE_LINECHART ? DataSeries::GROUPING_STANDARD : DataSeries::GROUPING_CLUSTERED,
            [0],
            [],
            [$etiquetas],
            [$valores]
        );

        $plotArea = new PlotArea(null, [$series]);
        $legend   = new Legend(Legend::POSITION_RIGHT, null, false);
        $titulo   = new Title($nombre);

        $chart = new Chart('grafico_' . $sheetName, $titulo, $legend, $plotArea);
        $chart->setTopLeftPosition('D2');
        $chart->setBottomRightPosition('N20');

        $hoja->addChart($chart);
    }

    /** Excel no permite /\*?:[] ni nombres de hoja de más de 31 caracteres. */
    private function normalizarNombreHoja(string $nombre): string
    {
        $nombre = str_replace(['/', '\\', '*', '?', ':', '[', ']'], '', $nombre);
        return substr($nombre, 0, 31);
    }
}