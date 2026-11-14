<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';

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

$idUsuario = (int) $_SESSION['id_usuario'];
$rol       = $_SESSION['rol'];

$repo = new PrestamoRepository();
$repo->marcarVencidos(); // así el estado "vencido" está al día al mirar la lista

$pendientes = [];
$devueltos  = [];
foreach ($repo->listarPorUsuario($idUsuario) as $p) {
    if ((int) $p['id_estado'] === PrestamoRepository::ESTADO_DEVUELTO) {
        $devueltos[] = $p;
    } else {
        $pendientes[] = $p;
    }
}
// Lo que vence primero, arriba.
usort($pendientes, fn($a, $b) => strcmp($a['fecha_vencimieto'], $b['fecha_vencimieto']));

$activos = $repo->contarActivosPorUsuario($idUsuario);
$limite  = PrestamoRepository::limitePorPerfil($rol);
$hoy     = new DateTimeImmutable('today');

/** Devuelve [clase, texto] según lo que falta (o lo que se pasó) para devolver. */
function situacion(array $p, DateTimeImmutable $hoy): array
{
    $vence = (new DateTimeImmutable($p['fecha_vencimieto']))->setTime(0, 0);
    $dias  = (int) $hoy->diff($vence)->format('%r%a'); // negativo = ya pasó
    $vencido = (int) $p['id_estado'] === PrestamoRepository::ESTADO_VENCIDO || $dias < 0;

    if ($vencido) {
        return ['vencido', $dias < 0 ? 'Vencido hace ' . abs($dias) . (abs($dias) === 1 ? ' día' : ' días') : 'Vencido'];
    }
    if ($dias === 0) return ['pronto', 'Vence hoy'];
    if ($dias === 1) return ['pronto', 'Vence mañana'];
    if ($dias <= 2)  return ['pronto', "Faltan $dias días"];
    return ['ok', "Faltan $dias días"];
}

$paginaActual = 'prestamos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Mis préstamos · ViBlio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar_usuario.css">
    <link rel="stylesheet" href="menu_usuario.css">
    <link rel="stylesheet" href="mis_prestamos.css">
</head>
<body>

<?php include __DIR__ . '/navbar_usuario.php'; ?>

<main class="contenido">
    <div class="encabezado">
        <h1>Mis préstamos</h1>
        <a class="salir" href="logout.php">Cerrar sesión</a>
    </div>
    <p class="lema">
        <?php if ($limite !== null): ?>
            Tenés <strong><?= $activos ?> de <?= $limite ?></strong> préstamos activos.
        <?php else: ?>
            Tenés <strong><?= $activos ?></strong> préstamo<?= $activos === 1 ? '' : 's' ?> activo<?= $activos === 1 ? '' : 's' ?>.
        <?php endif; ?>
        Las devoluciones se registran en la biblioteca.
    </p>

    <h2 class="seccion">Para devolver</h2>
    <?php if (!$pendientes): ?>
        <p class="vacio">No tenés libros pendientes de devolución. <a href="catalogo.php">Buscá uno en el catálogo</a>.</p>
    <?php else: ?>
        <ul class="lista">
            <?php foreach ($pendientes as $p): ?>
                <?php [$clase, $texto] = situacion($p, $hoy); ?>
                <li class="prestamo prestamo--<?= $clase ?>">
                    <div class="prestamo__datos">
                        <h3 class="prestamo__titulo"><?= h($p['titulo']) ?></h3>
                        <p class="prestamo__meta">
                            Retirado el <?= fecha($p['fecha_prestamo']) ?> · Vence el <strong><?= fecha($p['fecha_vencimieto']) ?></strong>
                            · Préstamo N.º <?= h((string) $p['codigo_prestamo']) ?>
                        </p>
                    </div>
                    <span class="etiqueta etiqueta--<?= $clase ?>"><?= h($texto) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h2 class="seccion">Ya devueltos</h2>
    <?php if (!$devueltos): ?>
        <p class="vacio">Todavía no devolviste ningún libro.</p>
    <?php else: ?>
        <ul class="lista">
            <?php foreach ($devueltos as $p): ?>
                <li class="prestamo prestamo--devuelto">
                    <div class="prestamo__datos">
                        <h3 class="prestamo__titulo"><?= h($p['titulo']) ?></h3>
                        <p class="prestamo__meta">
                            Retirado el <?= fecha($p['fecha_prestamo']) ?> · Devuelto el <?= fecha($p['fecha_devolucion']) ?>
                            · Préstamo N.º <?= h((string) $p['codigo_prestamo']) ?>
                        </p>
                    </div>
                    <span class="etiqueta etiqueta--devuelto">Devuelto</span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<footer class="pie">
    <strong>ViBlio</strong>
    <a href="mailto:contacto@viblio.com">contacto@viblio.com</a>
    <a href="sobre_nosotros.php">Sobre nosotros</a>
</footer>

</body>
</html>
