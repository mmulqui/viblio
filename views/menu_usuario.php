<!--Este archivo es solamente de prueba
La version final no sera asi -->

<?php
require_once("../controlers/auth.php");
verificarRol(['alumno', 'profesor']);

require_once("../models/conexion.php");

$objeto   = new conexion();
$conexion = $objeto->conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViBlio - Catálogo</title>
    <link rel="stylesheet" href="style_menu.css">
    <style>
        .badge-disponible {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-no-disponible {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .bienvenida {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php
        $busqueda    = $_GET["busqueda"] ?? "";
        $mostrartodos = isset($_GET["todos"]);

        if ($mostrartodos) {
            $sql = "SELECT libro.titulo,
                           libro.edicion,
                           libro.anio_publicacion,
                           libro.isbn,
                           libro.estado,
                           autor.nombre AS autor,
                           editorial.nombre AS editorial,
                           categoria.nombre AS categoria,
                           genero.nombre AS genero
                    FROM libro
                    JOIN rela_aut_lib ON libro.id_libro = rela_aut_lib.id_libro
                    JOIN autor ON rela_aut_lib.id_autor = autor.id_autor
                    JOIN rela_edit_lib ON libro.id_libro = rela_edit_lib.id_libro
                    JOIN editorial ON rela_edit_lib.id_editorial = editorial.id_editorial
                    JOIN rela_cat_lib_gen ON libro.id_libro = rela_cat_lib_gen.id_libro
                    JOIN categoria ON rela_cat_lib_gen.id_categoria = categoria.id_categoria
                    JOIN genero ON rela_cat_lib_gen.id_genero = genero.id_genero
                    WHERE libro.activo = 1";
        } else {
            $busquedaEsc = $conexion->real_escape_string($busqueda);
            $sql = "SELECT libro.titulo,
                           libro.edicion,
                           libro.anio_publicacion,
                           libro.isbn,
                           libro.estado,
                           autor.nombre AS autor,
                           editorial.nombre AS editorial,
                           categoria.nombre AS categoria,
                           genero.nombre AS genero
                    FROM libro
                    JOIN rela_aut_lib ON libro.id_libro = rela_aut_lib.id_libro
                    JOIN autor ON rela_aut_lib.id_autor = autor.id_autor
                    JOIN rela_edit_lib ON libro.id_libro = rela_edit_lib.id_libro
                    JOIN editorial ON rela_edit_lib.id_editorial = editorial.id_editorial
                    JOIN rela_cat_lib_gen ON libro.id_libro = rela_cat_lib_gen.id_libro
                    JOIN categoria ON rela_cat_lib_gen.id_categoria = categoria.id_categoria
                    JOIN genero ON rela_cat_lib_gen.id_genero = genero.id_genero
                    WHERE libro.activo = 1
                      AND (libro.titulo LIKE '%$busquedaEsc%'
                       OR autor.nombre LIKE '%$busquedaEsc%'
                       OR editorial.nombre LIKE '%$busquedaEsc%'
                       OR categoria.nombre LIKE '%$busquedaEsc%'
                       OR genero.nombre LIKE '%$busquedaEsc%'
                       OR libro.isbn LIKE '%$busquedaEsc%')";
        }

        $resultado = $objeto->consultar($sql, $conexion);
    ?>

    <!-- Barra lateral -->
    <div class="container">
        <div class="titulo">
            <h1>ViBlio</h1>
        </div>
        <div class="Menu">
            <ul>
                <li><a onclick="showtab('libros')"><ion-icon name="library-outline"></ion-icon>Catálogo</a></li>
                <li><a onclick="showtab('prestamos')"><ion-icon name="pricetag-outline"></ion-icon>Mis Préstamos</a></li>
                <li><a onclick="showtab('reservas')"><ion-icon name="bookmark-outline"></ion-icon>Mis Reservas</a></li>
                <li><a href="logout.php" onclick="return confirm('¿Cerrar sesión?')"><ion-icon name="log-out-outline"></ion-icon>Salir</a></li>
            </ul>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="main_content">

        <!-- TAB: Catálogo de libros -->
        <div id="libros" class="tab_content">
            <div class="encabezado">
                <h2>Catálogo de Libros</h2>
                <p class="bienvenida">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION["email"]); ?></strong></p>
            </div>
            <div class="content">
                <h3>Libros disponibles</h3>

                <!-- Buscador -->
                <div class="box">
                    <form method="GET">
                        <input type="text" name="busqueda" placeholder="Buscar por título, autor, ISBN..." value="<?= htmlspecialchars($busqueda) ?>">
                        <button><ion-icon name="search"></ion-icon></button>
                        <button type="submit" name="todos">Todos</button>
                    </form>
                </div>

                <!-- Tabla de libros -->
                <div class="tabla">
                    <table>
                        <thead>
                            <tr>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Edición</th>
                                <th>Autor</th>
                                <th>Editorial</th>
                                <th>Categoría</th>
                                <th>Género</th>
                                <th>Año</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0): ?>
                                <?php foreach ($resultado as $fila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila["isbn"]) ?></td>
                                    <td><?= htmlspecialchars($fila["titulo"]) ?></td>
                                    <td><?= htmlspecialchars($fila["edicion"]) ?></td>
                                    <td><?= htmlspecialchars($fila["autor"]) ?></td>
                                    <td><?= htmlspecialchars($fila["editorial"]) ?></td>
                                    <td><?= htmlspecialchars($fila["categoria"]) ?></td>
                                    <td><?= htmlspecialchars($fila["genero"]) ?></td>
                                    <td><?= htmlspecialchars($fila["anio_publicacion"]) ?></td>
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
                                <tr>
                                    <td colspan="9" style="text-align:center; padding: 30px; color:#999;">
                                        No se encontraron libros.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB: Mis Préstamos -->
        <div id="prestamos" class="tab_content">
            <div class="encabezado">
                <h2>Mis Préstamos</h2>
            </div>
            <div class="content">
                <p style="color:#999; padding: 20px;">Próximamente podrás ver tus préstamos activos aquí.</p>
            </div>
        </div>

        <!-- TAB: Mis Reservas -->
        <div id="reservas" class="tab_content">
            <div class="encabezado">
                <h2>Mis Reservas</h2>
            </div>
            <div class="content">
                <p style="color:#999; padding: 20px;">Próximamente podrás ver tus reservas aquí.</p>
            </div>
        </div>

    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        function showtab(tabId) {
            const tabs = document.querySelectorAll('.tab_content');
            tabs.forEach(tab => tab.style.display = 'none');
            document.getElementById(tabId).style.display = 'block';
        }
        window.onload = () => showtab('libros');
    </script>
</body>
</html>
