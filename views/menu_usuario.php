<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Modulos/ModuloRepository.php';
require_once dirname(__DIR__) . '/Libros/LibroRepository.php';

AuthGuard::verificarRol(['alumno', 'profesor']);

$id_usuario = (int) $_SESSION['id_usuario'];

$moduloRepo = new ModuloRepository();
$modulos    = $moduloRepo->obtenerMapaActivosPorUsuario($id_usuario);

$libroRepo    = new LibroRepository();
$busqueda     = $_GET["busqueda"] ?? "";
$mostrartodos = isset($_GET["todos"]);
$resultado    = $libroRepo->listarActivos($busqueda, $mostrartodos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViBlio - Catálogo</title>
    <link rel="stylesheet" href="style_menu.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .badge-disponible {
            background-color: #d4edda; color: #155724;
            border: 1px solid #c3e6cb; padding: 4px 10px;
            border-radius: 20px; font-size: 12px; font-weight: bold;
        }
        .badge-no-disponible {
            background-color: #f8d7da; color: #721c24;
            border: 1px solid #f5c6cb; padding: 4px 10px;
            border-radius: 20px; font-size: 12px; font-weight: bold;
        }
        .acceso-denegado {
            text-align: center; padding: 60px 20px; color: #999;
        }
        .acceso-denegado ion-icon { font-size: 60px; color: #ddd; display: block; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="container">
        <div class="titulo"><h1>ViBlio</h1></div>
        <div class="Menu">
            <ul>
                <?php if ($modulos['catalogo'] ?? false): ?>
                <li><a onclick="showtab('catalogo')"><ion-icon name="library-outline"></ion-icon>Catálogo</a></li>
                <?php endif; ?>
                <?php if ($modulos['prestamos'] ?? false): ?>
                <li><a onclick="showtab('prestamos')"><ion-icon name="pricetag-outline"></ion-icon>Mis Préstamos</a></li>
                <?php endif; ?>
                <?php if ($modulos['reservas'] ?? false): ?>
                <li><a onclick="showtab('reservas')"><ion-icon name="bookmark-outline"></ion-icon>Mis Reservas</a></li>
                <?php endif; ?>
                <li><a href="#" onclick="mdConfirm('¿Cerrar sesión?', function(){ window.location.href='logout.php'; })"><ion-icon name="log-out-outline"></ion-icon>Salir</a></li>
            </ul>
        </div>
    </div>

    <div class="main_content">

        <?php if ($modulos['catalogo'] ?? false): ?>
        <div id="catalogo" class="tab_content">
            <div class="encabezado">
                <h2>Catálogo de Libros</h2>
            </div>
            <div class="content">
                <h3>Libros disponibles</h3>



           


                <div class="box">
                    <form method="GET">
                        <input type="text" name="busqueda" placeholder="Buscar por título, autor, ISBN..." value="<?= htmlspecialchars($busqueda) ?>">
                        <button><ion-icon name="search"></ion-icon></button>
                        <button type="submit" name="todos">Todos</button>
                    </form>


                     



                    
                </div>
                <div class="tabla">
                    <table>
                        <thead>
                            <tr>
                                <th>ISBN</th><th>Título</th><th>Edición</th><th>Autor</th>
                                <th>Editorial</th><th>Categoría</th><th>Género</th><th>Año</th><th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($resultado) > 0): ?>
                                <?php foreach ($resultado as $fila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila["isbn"]) ?></td>
                                    <td><?= htmlspecialchars($fila["titulo"]) ?></td>
                                    <td><?= htmlspecialchars($fila["edicion"]) ?></td>
                                    <td><?= htmlspecialchars($fila["autor"]) ?></td>
                                    <td><?= htmlspecialchars($fila["editorial"]) ?></td>
                                    <td><?= htmlspecialchars($fila["categoria"]) ?></td>
                                    <td><?= htmlspecialchars($fila["genero"]) ?></td>
                                    <td><?= htmlspecialchars((string) $fila["anio_publicacion"]) ?></td>
                                    <td>
                                        <?php if ($fila["estado"] == 1): ?>
                                            <span class="badge-disponible">✓ Disponible</span>
                                        <?php else: ?>
                                            <span class="badge-no-disponible">✗ No disponible</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="9" style="text-align:center;padding:30px;color:#999;">No se encontraron libros.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($modulos['prestamos'] ?? false): ?>
        <div id="prestamos" class="tab_content">
            <div class="encabezado"><h2>Mis Préstamos</h2></div>
            <div class="content">
                <p style="color:#999;padding:20px;">Próximamente podrás ver tus préstamos activos aquí.</p>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($modulos['reservas'] ?? false): ?>
        <div id="reservas" class="tab_content">
            <div class="encabezado"><h2>Mis Reservas</h2></div>
            <div class="content">
                <p style="color:#999;padding:20px;">Próximamente podrás ver tus reservas aquí.</p>
            </div>
        </div>
        <?php endif; ?>

        <?php if (empty(array_filter($modulos))): ?>
        <div class="acceso-denegado">
            <ion-icon name="lock-closed-outline"></ion-icon>
            <h3>Sin módulos habilitados</h3>
            <p>El bibliotecario aún no habilitó ninguna sección para tu cuenta.</p>
        </div>
        <?php endif; ?>







        

    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="script_alertas.js"></script>
    <script>
        function showtab(tabId) {
            const tabs = document.querySelectorAll('.tab_content');
            tabs.forEach(tab => tab.style.display = 'none');
            const el = document.getElementById(tabId);
            if (el) el.style.display = 'block';
        }

        window.onload = () => {
            const primero = document.querySelector('.tab_content');
            if (primero) primero.style.display = 'block';
        };
    </script>

    <?php if (isset($_SESSION['alerta'])): 
        $a = $_SESSION['alerta'];
        unset($_SESSION['alerta']);
    ?>
    <script>
        mdAlert('<?= $a['tipo'] ?>', '<?= $a['titulo'] ?>', '<?= addslashes($a['msg']) ?>');
    </script>
    <?php endif; ?>

</body>
</html>
