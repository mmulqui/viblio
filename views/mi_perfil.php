<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Usuarios/UsuarioRepository.php';

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

$u = (new UsuarioRepository())->obtenerPorId((int) $_SESSION['id_usuario']);
if (!$u) {
    header('Location: logout.php');
    exit;
}

$paginaActual = 'perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Mi perfil · ViBlio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar_usuario.css">
    <link rel="stylesheet" href="menu_usuario.css">
    <link rel="stylesheet" href="mi_perfil.css">
</head>
<body>

<?php include __DIR__ . '/navbar_usuario.php'; ?>

<main class="contenido">
    <div class="encabezado">
        <h1>Mi perfil</h1>
        <a class="salir" href="logout.php">Cerrar sesión</a>
    </div>
    <p class="lema">Mantené tus datos al día. El DNI y el tipo de cuenta los administra la biblioteca.</p>

    <section class="tarjeta" aria-labelledby="t-cuenta">
        <h2 id="t-cuenta">Mi cuenta</h2>
        <dl class="datos">
            <div><dt>DNI</dt><dd><?= h($u['dni']) ?></dd></div>
            <div><dt>Tipo de cuenta</dt><dd><?= h(ucfirst($u['rol'])) ?></dd></div>
            <div><dt>Email</dt><dd><?= h($u['email']) ?></dd></div>
        </dl>
    </section>

    <section class="tarjeta" aria-labelledby="t-datos">
        <h2 id="t-datos">Datos personales</h2>
        <form method="post" action="../controlers/procesar_mi_perfil.php">
            <input type="hidden" name="accion" value="datos">
            <div class="fila">
                <label>Nombre
                    <input type="text" name="nombre" value="<?= h($u['nombre']) ?>" maxlength="50" required autocomplete="given-name">
                </label>
                <label>Apellido
                    <input type="text" name="apellido" value="<?= h($u['apellido']) ?>" maxlength="50" required autocomplete="family-name">
                </label>
            </div>
            <label>Fecha de nacimiento
                <input type="date" name="fecha_nacimiento" value="<?= h($u['fecha_nacimiento']) ?>"
                       min="1900-01-01" max="<?= date('Y-m-d') ?>" required autocomplete="bday">
            </label>
            <button type="submit">Guardar datos</button>
        </form>
    </section>

    <section class="tarjeta" aria-labelledby="t-email">
        <h2 id="t-email">Cambiar email</h2>
        <p class="ayuda">Es el que usás para iniciar sesión. Por seguridad te pedimos tu contraseña actual.</p>
        <form method="post" action="../controlers/procesar_mi_perfil.php">
            <input type="hidden" name="accion" value="email">
            <label>Email nuevo
                <input type="email" name="email" maxlength="100" required autocomplete="email">
            </label>
            <label>Contraseña actual
                <input type="password" name="actual" required autocomplete="current-password">
            </label>
            <button type="submit">Cambiar email</button>
        </form>
    </section>

    <section class="tarjeta" aria-labelledby="t-pass">
        <h2 id="t-pass">Cambiar contraseña</h2>
        <p class="ayuda">Mínimo 8 caracteres, con al menos una mayúscula y un carácter especial.</p>
        <form method="post" action="../controlers/procesar_mi_perfil.php">
            <input type="hidden" name="accion" value="contrasenia">
            <label>Contraseña actual
                <input type="password" name="actual" required autocomplete="current-password">
            </label>
            <div class="fila">
                <label>Contraseña nueva
                    <input type="password" name="nueva" minlength="8" required autocomplete="new-password">
                </label>
                <label>Repetí la contraseña nueva
                    <input type="password" name="confirmar" minlength="8" required autocomplete="new-password">
                </label>
            </div>
            <button type="submit">Cambiar contraseña</button>
        </form>
    </section>
</main>

<footer class="pie">
    <strong>ViBlio</strong>
    <a href="mailto:contacto@viblio.com">contacto@viblio.com</a>
    <a href="sobre_nosotros.php">Sobre nosotros</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="script_alertas.js"></script>
<script>
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
