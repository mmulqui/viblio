<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';
require_once dirname(__DIR__) . '/Reservas/ReservaRepository.php';

AuthGuard::verificarSesion();
if (($_SESSION['rol'] ?? '') === 'bibliotecario') {
    header('Location: menu.php');
    exit;
}
AuthGuard::verificarRol(['alumno', 'profesor']);

function h(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

function fecha(?string $f): string
{
    return $f ? (new DateTimeImmutable($f))->format('d/m/Y') : '—';
}

const MESES = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio',
               'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

$idUsuario = (int) $_SESSION['id_usuario'];
$filtro    = $_GET['tipo'] ?? 'todos';
if (!in_array($filtro, ['todos', 'prestamos', 'reservas'], true)) {
    $filtro = 'todos';
}

$prestamoRepo = new PrestamoRepository();
$prestamoRepo->marcarVencidos();

// Una sola lista con préstamos y reservas, cada uno con su fecha de referencia.
$movimientos = [];
if ($filtro !== 'reservas') {
    foreach ($prestamoRepo->listarPorUsuario($idUsuario) as $p) {
        $est = (int) $p['id_estado'];
        [$clase, $texto] = match ($est) {
            PrestamoRepository::ESTADO_ACTIVO   => ['ok', 'Activo'],
            PrestamoRepository::ESTADO_VENCIDO  => ['vencido', 'Vencido'],
            default                             => ['devuelto', 'Devuelto'],
        };
        $detalle = 'Retirado el ' . fecha($p['fecha_prestamo']) . ' · Vence el ' . fecha($p['fecha_vencimieto']);
        if (!empty($p['fecha_devolucion'])) {
            $detalle .= ' · Devuelto el ' . fecha($p['fecha_devolucion']);
        }
        $movimientos[] = [
            'tipo' => 'Préstamo', 'fecha' => $p['fecha_prestamo'], 'titulo' => $p['titulo'],
            'detalle' => $detalle . ' · N.º ' . $p['codigo_prestamo'], 'clase' => $clase, 'texto' => $texto,
        ];
    }
}
if ($filtro !== 'prestamos') {
    foreach ((new ReservaRepository())->listarPorUsuario($idUsuario) as $r) {
        $est = (int) $r['id_estado'];
        [$clase, $texto] = match ($est) {
            ReservaRepository::ESTADO_PENDIENTE => ['ok', 'Pendiente'],
            ReservaRepository::ESTADO_CUMPLIDA  => ['devuelto', 'Cumplida'],
            default                             => ['devuelto', 'Cancelada'],
        };
        $movimientos[] = [
            'tipo' => 'Reserva', 'fecha' => $r['fecha_solicitud'], 'titulo' => $r['titulo'],
            'detalle' => 'Reservado el ' . fecha($r['fecha_solicitud']) . ' · N.º ' . $r['codigo_reserva'],
            'clase' => $clase, 'texto' => $texto,
        ];
    }
}
usort($movimientos, fn($a, $b) => strcmp($b['fecha'], $a['fecha']));

// Agrupar por mes ("noviembre 2026").
$porMes = [];
foreach ($movimientos as $m) {
    $d = new DateTimeImmutable($m['fecha']);
    $porMes[MESES[(int) $d->format('n') - 1] . ' ' . $d->format('Y')][] = $m;
}

$filtros = ['todos' => 'Todo', 'prestamos' => 'Préstamos', 'reservas' => 'Reservas'];
$paginaActual = 'historial';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Historial · ViBlio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar_usuario.css">
    <link rel="stylesheet" href="menu_usuario.css">
    <link rel="stylesheet" href="mis_prestamos.css">
    <link rel="stylesheet" href="historial.css">
</head>
<body>

<?php include __DIR__ . '/navbar_usuario.php'; ?>

<main class="contenido">
    <div class="encabezado">
        <h1>Historial</h1>
        <a class="salir" href="logout.php">Cerrar sesión</a>
    </div>
    <p class="lema">Todo lo que pediste y reservaste, del más reciente al más antiguo.</p>

    <nav class="filtros" aria-label="Filtrar historial">
        <?php foreach ($filtros as $clave => $etiqueta): ?>
            <a href="historial.php?tipo=<?= $clave ?>"<?= $clave === $filtro ? ' class="is-activo" aria-current="true"' : '' ?>><?= $etiqueta ?></a>
        <?php endforeach; ?>
    </nav>

    <?php if (!$movimientos): ?>
        <p class="vacio">Todavía no hay movimientos. <a href="catalogo.php">Buscá un libro en el catálogo</a>.</p>
    <?php else: ?>
        <?php foreach ($porMes as $mes => $items): ?>
            <h2 class="seccion mes"><?= h($mes) ?></h2>
            <ul class="lista">
                <?php foreach ($items as $m): ?>
                    <li class="prestamo prestamo--<?= $m['clase'] ?>">
                        <div class="prestamo__datos">
                            <p class="tipo"><?= $m['tipo'] ?></p>
                            <h3 class="prestamo__titulo"><?= h($m['titulo']) ?></h3>
                            <p class="prestamo__meta"><?= h($m['detalle']) ?></p>
                        </div>
                        <span class="etiqueta etiqueta--<?= $m['clase'] ?>"><?= $m['texto'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<footer class="pie">
    <strong>ViBlio</strong>
    <a href="mailto:contacto@viblio.com">contacto@viblio.com</a>
    <a href="sobre_nosotros.php">Sobre nosotros</a>
</footer>

</body>
</html>
