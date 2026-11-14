<?php
/*
 * Barra de navegación de alumnos y profesores (vive en views/).
 * Uso en cada página de usuario:
 *   $paginaActual = 'inicio';   // inicio | libros | prestamos | reservas | historial | multas | perfil
 *   include __DIR__ . '/navbar_usuario.php';
 * Las URLs son relativas a views/. Las páginas que todavía no existen
 * (catalogo, mis_prestamos, mis_reservas, historial, mis_multas, mi_perfil) hay que crearlas.
 */
$paginaActual = $paginaActual ?? '';

$items = [
    'inicio'    => ['Inicio',    'menu_usuario.php',  '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
    'libros'    => ['Libros',    'catalogo.php',      '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>'],
    'prestamos' => ['Préstamos', 'mis_prestamos.php', '<polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>'],
    'reservas'  => ['Reservas',  'mis_reservas.php',  '<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>'],
    'historial' => ['Historial', 'historial.php',     '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
    'multas'    => ['Multas',    'mis_multas.php',    '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'],
    'perfil'    => ['Perfil',    'mi_perfil.php',     '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
];
?>
<nav class="navbar" aria-label="Navegación principal">
    <ul class="navbar__menu">
        <?php foreach ($items as $clave => [$etiqueta, $url, $icono]): ?>
            <?php $activo = ($clave === $paginaActual); ?>
            <li class="navbar__item">
                <a href="<?= $url ?>" class="navbar__link<?= $activo ? ' is-active' : '' ?>"<?= $activo ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $icono ?></svg>
                    <span><?= $etiqueta ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
