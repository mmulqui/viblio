<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Multas/MultaRepository.php';

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

const MOTIVOS = [
    'retraso'    => 'Retraso en la devolución',
    'rayado'     => 'Libro devuelto rayado',
    'maltratado' => 'Libro devuelto maltratado',
    'roto'       => 'Libro devuelto roto',
];

$idUsuario = (int) $_SESSION['id_usuario'];
$repo      = new MultaRepository();
$multas    = $repo->listarPorUsuario($idUsuario);

$hasta = $repo->sancionVigenteHasta($idUsuario);
$diasRestantes = null;
if ($hasta) {
    $segundos = strtotime($hasta) - time();
    $diasRestantes = max(1, (int) ceil($segundos / 86400));
}

$estados = [
    'vigente'  => ['vencido',  'Vigente'],
    'cumplida' => ['devuelto', 'Cumplida'],
    'anulada'  => ['devuelto', 'Anulada'],
];

$paginaActual = 'multas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Mis multas · ViBlio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar_usuario.css">
    <link rel="stylesheet" href="menu_usuario.css">
    <link rel="stylesheet" href="mis_prestamos.css">
    <link rel="stylesheet" href="mis_multas.css">
</head>
<body>

<?php include __DIR__ . '/navbar_usuario.php'; ?>

<main class="contenido">
    <div class="encabezado">
        <h1>Mis multas</h1>
        <a class="salir" href="logout.php">Cerrar sesión</a>
    </div>
    <p class="lema">Las sanciones se aplican por devolver un libro con retraso o en mal estado.</p>

    <?php if ($hasta): ?>
        <section class="situacion situacion--alerta" role="alert">
            <h2>Tenés una sanción vigente</h2>
            <p>
                Hasta el <strong><?= h(date('d/m/Y H:i', strtotime($hasta))) ?></strong>
                (<?= $diasRestantes ?> día<?= $diasRestantes === 1 ? '' : 's' ?>).
                Mientras dure no podés pedir libros en préstamo ni reservar.
            </p>
        </section>
    <?php else: ?>
        <section class="situacion" role="status">
            <h2>No tenés sanciones vigentes</h2>
            <p>Podés pedir libros y reservar con normalidad.</p>
        </section>
    <?php endif; ?>

    <h2 class="seccion">Detalle</h2>
    <?php if (!$multas): ?>
        <p class="vacio">Nunca tuviste una multa. ¡Seguí así!</p>
    <?php else: ?>
        <ul class="lista">
            <?php foreach ($multas as $m): ?>
                <?php [$clase, $texto] = $estados[$m['estado_calculado']]; ?>
                <li class="prestamo prestamo--<?= $clase ?>">
                    <div class="prestamo__datos">
                        <h3 class="prestamo__titulo"><?= h(MOTIVOS[$m['motivo']] ?? $m['motivo']) ?></h3>
                        <p class="prestamo__meta">
                            <?php if (!empty($m['titulo'])): ?>Libro: <strong><?= h($m['titulo']) ?></strong> · <?php endif; ?>
                            <?= (int) $m['dias_sancion'] ?> día<?= (int) $m['dias_sancion'] === 1 ? '' : 's' ?> de sanción
                            · Del <?= fecha($m['fecha_inicio']) ?> al <?= fecha($m['fecha_fin']) ?>
                        </p>
                        <?php if (!empty($m['observaciones'])): ?>
                            <p class="prestamo__meta">Observaciones: <?= h($m['observaciones']) ?></p>
                        <?php endif; ?>
                    </div>
                    <span class="etiqueta etiqueta--<?= $clase ?>"><?= $texto ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="nota">Si tenés varias sanciones, se suman: cada una empieza cuando termina la anterior.</p>
    <?php endif; ?>
</main>

<footer class="pie">
    <strong>ViBlio</strong>
    <a href="mailto:contacto@viblio.com">contacto@viblio.com</a>
    <a href="sobre_nosotros.php">Sobre nosotros</a>
</footer>

</body>
</html>
