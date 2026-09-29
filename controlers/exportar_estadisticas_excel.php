<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Estadisticas/EstadisticaRepository.php';
require_once dirname(__DIR__) . '/Estadisticas/EstadisticaExportador.php';

AuthGuard::verificarRol(['bibliotecario']);

$soloGrafico = $_GET['grafico'] ?? null;

$datos = (new EstadisticaRepository())->obtenerTodo();
(new EstadisticaExportador())->generarYDescargar($datos, $soloGrafico);