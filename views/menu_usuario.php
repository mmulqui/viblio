<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Usuarios/UsuarioRepository.php';
require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';
require_once dirname(__DIR__) . '/Multas/MultaRepository.php';

// Sesión válida; el bibliotecario va a su propio menú y el resto de roles no entra.
AuthGuard::verificarSesion();
if (($_SESSION['rol'] ?? '') === 'bibliotecario') {
    header('Location: menu.php');
    exit;
}
AuthGuard::verificarRol(['alumno', 'profesor']);

$idUsuario = (int) $_SESSION['id_usuario'];
$rol       = $_SESSION['rol'];

$usuarioRepo  = new UsuarioRepository();
$prestamoRepo = new PrestamoRepository();
$multaRepo    = new MultaRepository();

$usuario = $usuarioRepo->obtenerPorId($idUsuario);
$nombre  = htmlspecialchars($usuario['nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8');

// Préstamos activos y tope según el perfil (alumno: 3, profesor: sin tope).
$prestamosActivos = $prestamoRepo->contarActivosPorUsuario($idUsuario);
$limite           = PrestamoRepository::limitePorPerfil($rol);

// Próxima devolución: el vencimiento más cercano entre los préstamos activos.
$proximaDevolucion = null;
$vencimientos = [];
foreach ($prestamoRepo->listarPorUsuario($idUsuario) as $p) {
    if ((int) $p['id_estado'] === PrestamoRepository::ESTADO_ACTIVO) {
        $vencimientos[] = $p['fecha_vencimieto'];
    }
}
if ($vencimientos) {
    sort($vencimientos);
    $proximaDevolucion = (new DateTime($vencimientos[0]))->format('d/m/Y');
}

// Multas: las sanciones son por tiempo, así que se muestra hasta cuándo dura la vigente.
$sancionHasta = $multaRepo->sancionVigenteHasta($idUsuario);
$sancionHasta = $sancionHasta ? (new DateTime($sancionHasta))->format('d/m/Y') : null;

$paginaActual = 'inicio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>ViBlio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar_usuario.css">
    <link rel="stylesheet" href="menu_usuario.css">
</head>
<body>

<?php include __DIR__ . '/navbar_usuario.php'; ?>

<main class="contenido">
    <div class="encabezado">
        <h1>¡Bienvenido, <?= $nombre ?>!</h1>
        <a class="salir" href="logout.php">Cerrar sesión</a>
    </div>
    <p class="lema">Donde cada libro encuentra su lugar y cada lector, una nueva historia.</p>

    <section class="resumen" aria-label="Tu resumen">
        <a class="dato" href="mis_prestamos.php">
            <span class="dato__valor">
                <?= $prestamosActivos ?><?php if ($limite !== null): ?> de <?= $limite ?><?php endif; ?>
            </span>
            <span class="dato__texto">Préstamos activos</span>
        </a>
        <a class="dato" href="mis_prestamos.php">
            <span class="dato__valor"><?= $proximaDevolucion ?? '—' ?></span>
            <span class="dato__texto">Próxima devolución</span>
        </a>
        <a class="dato <?= $sancionHasta ? 'dato--alerta' : '' ?>" href="mis_multas.php">
            <span class="dato__valor"><?= $sancionHasta ? 'Hasta ' . $sancionHasta : 'Sin multas' ?></span>
            <span class="dato__texto"><?= $sancionHasta ? 'Sanción vigente' : 'Multas' ?></span>
        </a>
    </section>

    <figure class="hero">
        <img src="img/biblioteca.jpg" alt="Biblioteca con estantes de madera en forma curva">
    </figure>
</main>

<footer class="pie">
    <strong>ViBlio</strong>
    <a href="mailto:contacto@viblio.com">contacto@viblio.com</a>
    <a href="sobre_nosotros.php">Sobre nosotros</a>
</footer>

</body>
</html>