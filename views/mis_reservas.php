<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
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

$idUsuario = (int) $_SESSION['id_usuario'];

$pendientes = [];
$historial  = [];
foreach ((new ReservaRepository())->listarPorUsuario($idUsuario) as $r) {
    if ((int) $r['id_estado'] === ReservaRepository::ESTADO_PENDIENTE) {
        $pendientes[] = $r;
    } else {
        $historial[] = $r;
    }
}

$paginaActual = 'reservas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Mis reservas · ViBlio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar_usuario.css">
    <link rel="stylesheet" href="menu_usuario.css">
    <link rel="stylesheet" href="mis_prestamos.css">
    <link rel="stylesheet" href="mis_reservas.css">
</head>
<body>

<?php include __DIR__ . '/navbar_usuario.php'; ?>

<main class="contenido">
    <div class="encabezado">
        <h1>Mis reservas</h1>
        <a class="salir" href="logout.php">Cerrar sesión</a>
    </div>
    <p class="lema">
        Tenés <strong><?= count($pendientes) ?></strong> reserva<?= count($pendientes) === 1 ? '' : 's' ?> pendiente<?= count($pendientes) === 1 ? '' : 's' ?>.
        Una reserva se marca como cumplida cuando retirás el libro en la biblioteca.
    </p>

    <h2 class="seccion">Pendientes</h2>
    <?php if (!$pendientes): ?>
        <p class="vacio">No tenés reservas pendientes. <a href="catalogo.php">Buscá un libro en el catálogo</a>.</p>
    <?php else: ?>
        <ul class="lista">
            <?php foreach ($pendientes as $r): ?>
                <li class="prestamo prestamo--ok">
                    <div class="prestamo__datos">
                        <h3 class="prestamo__titulo"><?= h($r['titulo']) ?></h3>
                        <p class="prestamo__meta">
                            Reservado el <?= fecha($r['fecha_solicitud']) ?>
                            · Reserva N.º <strong><?= h((string) $r['codigo_reserva']) ?></strong>
                        </p>
                    </div>
                    <div class="acciones">
                        <span class="etiqueta etiqueta--ok">Pendiente</span>
                        <form method="post" action="../controlers/procesar_reserva.php"
                              class="js-confirmar" data-titulo="Cancelar reserva"
                              data-msg="¿Cancelar la reserva de «<?= h($r['titulo']) ?>»?">
                            <input type="hidden" name="accion" value="cancelar">
                            <input type="hidden" name="id_reserva" value="<?= (int) $r['id_reserva'] ?>">
                            <input type="hidden" name="volver" value="mis_reservas.php">
                            <button type="submit" class="btn-cancelar">Cancelar</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h2 class="seccion">Historial</h2>
    <?php if (!$historial): ?>
        <p class="vacio">Todavía no tenés reservas cumplidas ni canceladas.</p>
    <?php else: ?>
        <ul class="lista">
            <?php foreach ($historial as $r): ?>
                <?php $cumplida = (int) $r['id_estado'] === ReservaRepository::ESTADO_CUMPLIDA; ?>
                <li class="prestamo prestamo--devuelto">
                    <div class="prestamo__datos">
                        <h3 class="prestamo__titulo"><?= h($r['titulo']) ?></h3>
                        <p class="prestamo__meta">
                            Reservado el <?= fecha($r['fecha_solicitud']) ?>
                            · Reserva N.º <?= h((string) $r['codigo_reserva']) ?>
                        </p>
                    </div>
                    <span class="etiqueta etiqueta--devuelto"><?= $cumplida ? 'Cumplida' : 'Cancelada' ?></span>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="script_alertas.js"></script>
<script>
    // Confirmación antes de cancelar
    document.querySelectorAll('form.js-confirmar').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            e.preventDefault();
            mdConfirm(f.dataset.msg, function () { f.submit(); }, f.dataset.titulo);
        });
    });

    // Resultado de la última acción (lo deja el controlador en $_SESSION['alerta'])
    <?php if (isset($_SESSION['alerta'])):
        $a = $_SESSION['alerta'];
        unset($_SESSION['alerta']);
        $flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT; ?>
    mdAlert(<?= json_encode($a['tipo'], $flags) ?>, <?= json_encode($a['titulo'], $flags) ?>, <?= json_encode($a['msg'], $flags) ?>);
    <?php endif; ?>
</script>
</body>
</html>
