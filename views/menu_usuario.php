<?php
session_start();

// AJUSTAR: usá los nombres de sesión que ya tenés en el login.
if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['rol'] ?? '', ['alumno', 'profesor'], true)) {
    header('Location: login.php');
    exit;
}

$rol    = $_SESSION['rol'];
$nombre = htmlspecialchars($_SESSION['nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8');

// TODO: reemplazar por datos reales (modelo de préstamos).
$prestamosActivos = 0;
$proximaDevolucion = null; // 'd/m/Y' o null
$limite = ($rol === 'alumno') ? 3 : null; // el profesor no tiene tope
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ViBlio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href=" menu_usuario.css">
</head>
<body>

<div class="cinta" aria-hidden="true">ViBlio</div>

<header class="barra-wrap">
    <nav class="barra" aria-label="Navegación principal">
        <button class="barra__btn" id="btnMenu" aria-label="Abrir menú" aria-expanded="false" aria-controls="menuLateral">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <a href="catalogo.php">Libros</a>
        <a href="mis_multas.php">Mis multas</a>
        <a href="catalogo.php?buscar=1" class="barra__btn" aria-label="Buscar libros">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
        </a>
    </nav>

    <!-- Menú desplegable: perfil y cierre de sesión -->
    <div class="menu-lateral" id="menuLateral" hidden>
        <p class="menu-lateral__usuario"><?= $nombre ?> <span>(<?= $rol ?>)</span></p>
        <a href="mi_perfil.php">Mi perfil</a>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</header>

<main class="contenido">
    <h1>¡Bienvenido, <?= $nombre ?>!</h1>
    <p class="lema">Donde cada libro encuentra su lugar y cada lector, una nueva historia.</p>

    <p class="resumen">
        <?php if ($limite !== null): ?>
            Tenés <strong><?= $prestamosActivos ?> de <?= $limite ?></strong> préstamos activos.
        <?php else: ?>
            Tenés <strong><?= $prestamosActivos ?></strong> préstamos activos.
        <?php endif; ?>
        <?php if ($proximaDevolucion): ?>
            Próxima devolución: <strong><?= $proximaDevolucion ?></strong>.
        <?php endif; ?>
    </p>

    <figure class="hero">
        <img src="img/biblioteca.jpg" alt="Biblioteca con estantes de madera en forma curva">
    </figure>

    <h2 class="pregunta">¿Qué querés hacer hoy?</h2>

    <div class="acciones">
        <a class="accion" href="mis_prestamos.php">
            <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M5 4h13a1 1 0 0 1 1 1v15H6a1 1 0 0 1-1-1V4z"/><path d="M5 17h14"/></svg>
            <span>Préstamos</span>
        </a>
        <a class="accion" href="mis_reservas.php">
            <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M6 3h12v18l-6-4-6 4V3z"/></svg>
            <span>Reservas</span>
        </a>
        <a class="accion" href="historial.php">
            <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/></svg>
            <span>Historial</span>
        </a>
    </div>
</main>

<footer class="pie">
    <strong class="pie__marca">ViBlio</strong>
    <div class="pie__fila">
        <span>Contáctanos:</span>
        <a href="mailto:contacto@viblio.com" aria-label="Correo">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
        </a>
        <a href="sobre_nosotros.php" class="pie__info">Sobre nosotros</a>
    </div>
</footer>

<script>
    const btn = document.getElementById('btnMenu');
    const menu = document.getElementById('menuLateral');
    btn.addEventListener('click', () => {
        const abierto = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!abierto));
        menu.hidden = abierto;
    });
</script>
</body>
</html>