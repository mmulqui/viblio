<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Libros/LibroRepository.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';
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

$idUsuario = (int) $_SESSION['id_usuario'];
$rol       = $_SESSION['rol'];
$busqueda  = trim($_GET['q'] ?? '');

// Estado del usuario: sanción vigente y tope de préstamos.
$sancionHasta = (new MultaRepository())->sancionVigenteHasta($idUsuario);
$sancionTexto = $sancionHasta ? date('d/m/Y H:i', strtotime($sancionHasta)) : null;

$prestamoRepo     = new PrestamoRepository();
$prestamosActivos = $prestamoRepo->contarActivosPorUsuario($idUsuario);
$limite           = PrestamoRepository::limitePorPerfil($rol);
$enLimite         = $limite !== null && $prestamosActivos >= $limite;

// listarActivos() devuelve una fila por cada combinación autor/editorial/categoría:
// se agrupa por libro para no mostrar el mismo título repetido.
$libros = [];
foreach ((new LibroRepository())->listarActivos($busqueda) as $f) {
    $id = (int) $f['id_libro'];
    $libros[$id] ??= [
        'titulo'  => $f['titulo'],
        'anio'    => $f['anio_publicacion'],
        'stock'   => (int) $f['stock'],
        'autores' => [], 'editoriales' => [], 'categorias' => [], 'generos' => [],
    ];
    foreach (['autor' => 'autores', 'editorial' => 'editoriales', 'categoria' => 'categorias', 'genero' => 'generos'] as $col => $lista) {
        if (!empty($f[$col]) && !in_array($f[$col], $libros[$id][$lista], true)) {
            $libros[$id][$lista][] = $f[$col];
        }
    }
}

$paginaActual = 'libros';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Libros · ViBlio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar_usuario.css">
    <link rel="stylesheet" href="menu_usuario.css">
    <link rel="stylesheet" href="catalogo.css">
</head>
<body>

<?php include __DIR__ . '/navbar_usuario.php'; ?>

<main class="contenido">
    <div class="encabezado">
        <h1>Libros</h1>
        <a class="salir" href="logout.php">Cerrar sesión</a>
    </div>
    <p class="lema">Buscá un libro y pedilo en préstamo o reservalo.</p>

    <?php if ($sancionTexto): ?>
        <p class="aviso aviso--alerta" role="alert">
            Tenés una sanción vigente hasta el <strong><?= h($sancionTexto) ?></strong>:
            no podés pedir libros ni reservar hasta esa fecha.
        </p>
    <?php elseif ($enLimite): ?>
        <p class="aviso" role="status">
            Ya tenés <strong><?= $prestamosActivos ?> de <?= $limite ?></strong> préstamos activos.
            Podés reservar, pero para pedir otro libro tenés que devolver uno.
        </p>
    <?php endif; ?>

    <form class="buscador" method="get" action="catalogo.php" role="search">
        <label class="sr-only" for="q">Buscar libros</label>
        <input id="q" type="search" name="q" value="<?= h($busqueda) ?>"
               placeholder="Título, autor, editorial, categoría o ISBN">
        <button type="submit">Buscar</button>
        <?php if ($busqueda !== ''): ?><a href="catalogo.php">Limpiar</a><?php endif; ?>
    </form>

    <?php if (!$libros): ?>
        <p class="vacio">
            <?= $busqueda !== '' ? 'No encontramos libros para “' . h($busqueda) . '”.' : 'Todavía no hay libros cargados.' ?>
        </p>
    <?php else: ?>
        <section class="libros" aria-label="Libros">
            <?php foreach ($libros as $id => $l): ?>
                <?php $hayStock = $l['stock'] > 0; ?>
                <article class="libro">
                    <h2 class="libro__titulo"><?= h($l['titulo']) ?></h2>
                    <p class="libro__autor"><?= h(implode(', ', $l['autores']) ?: 'Autor sin cargar') ?></p>
                    <p class="libro__meta">
                        <?= h(implode(', ', $l['editoriales'])) ?>
                        <?= $l['anio'] ? '· ' . h((string) $l['anio']) : '' ?>
                    </p>
                    <p class="libro__meta"><?= h(implode(', ', array_merge($l['categorias'], $l['generos']))) ?></p>
                    <p class="libro__stock <?= $hayStock ? '' : 'libro__stock--agotado' ?>">
                        <?= $hayStock ? 'Disponible: ' . $l['stock'] . ($l['stock'] === 1 ? ' ejemplar' : ' ejemplares') : 'Sin stock' ?>
                    </p>

                    <div class="libro__acciones">
                        <form method="post" action="../controlers/procesar_prestamo.php"
                              class="js-confirmar" data-titulo="Pedir préstamo"
                              data-msg="¿Pedir en préstamo «<?= h($l['titulo']) ?>»? Tenés una semana para devolverlo.">
                            <input type="hidden" name="accion" value="solicitar">
                            <input type="hidden" name="id_libro" value="<?= $id ?>">
                            <input type="hidden" name="volver" value="catalogo.php">
                            <button type="submit" <?= (!$hayStock || $sancionTexto || $enLimite) ? 'disabled' : '' ?>>Pedir préstamo</button>
                        </form>
                        <form method="post" action="../controlers/procesar_reserva.php"
                              class="js-confirmar" data-titulo="Reservar"
                              data-msg="¿Reservar «<?= h($l['titulo']) ?>»?">
                            <input type="hidden" name="accion" value="solicitar">
                            <input type="hidden" name="id_libro" value="<?= $id ?>">
                            <input type="hidden" name="volver" value="catalogo.php">
                            <button type="submit" class="btn-sec" <?= $sancionTexto ? 'disabled' : '' ?>>Reservar</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
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
    // Confirmación antes de pedir o reservar
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
