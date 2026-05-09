<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Gestion</title>
    <link rel="stylesheet" href="style_menu.css">
</head>
<body>
    <?php
        session_start();
        require_once("../models/conexion.php");
        require_once("../controlers/user_sesion.php");
        $objeto = new conexion();
        $conexion = $objeto->conectar();
        $id_usuario_logueado = (int) $_SESSION["id_usuario"];
        $busqueda = $_GET["busqueda"] ?? "";
        $mostrartodos = isset($_GET["todos"]);
        if($mostrartodos){
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
        JOIN genero ON rela_cat_lib_gen.id_genero = genero.id_genero";
        } else {$sql = "SELECT libro.titulo,
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
                                            WHERE libro.titulo LIKE '%$busqueda%'
                                            OR autor.nombre LIKE '%$busqueda%'
                                            OR editorial.nombre LIKE '%$busqueda%'
                                            OR categoria.nombre LIKE '%$busqueda%'
                                            OR genero.nombre LIKE '%$busqueda%'
                                            OR libro.isbn LIKE '%$busqueda%';";}
        $resultado = $objeto->consultar($sql, $conexion);
        $categorias = $objeto->consultar("select * from estado",$conexion);

        $busqueda_usuarios = $_GET["busqueda_usuarios"] ?? "";
        $todos_usuarios = isset($_GET["todos_usuarios"]);
        if($todos_usuarios){
            $sql_u = "SELECT 
                        u.id_usuario, 
                        p.nombre, 
                        p.apellido, 
                        p.fecha_nacimiento,
                        p.dni,
                        u.email,
                        pf.tipo_perfil AS rol,
                        a.numero_prestamos,
                        a.numero_multas
                    FROM persona p
                    JOIN usuario u ON p.id_persona = u.persona_id_persona
                    JOIN perfil pf ON u.id_perfil = pf.id_perfil
                    JOIN alumno a ON u.id_usuario = a.usuario_id_usuario
                    WHERE pf.tipo_perfil = 'alumno';";
        } else { $sql_u = "SELECT 
                                u.id_usuario
                                p.dni,
                                p.nombre,
                                p.apellido,
                                p.fecha_nacimiento,
                                u.email,
                                pf.tipo_perfil AS rol,
                                a.numero_prestamos,
                                a.numero_multas
                            FROM persona p
                            JOIN usuario u ON p.id_persona = u.persona_id_persona
                            JOIN perfil pf ON u.id_perfil = pf.id_perfil
                            JOIN alumno a ON u.id_usuario = a.usuario_id_usuario
                            WHERE pf.tipo_perfil = 'alumno'
                                        AND (p.nombre LIKE '%$busqueda_usuarios%' OR
                                            p.apellido LIKE '%$busqueda_usuarios%' OR
                                            p.dni LIKE '%$busqueda_usuarios%' OR
                                            u.email LIKE '%$busqueda_usuarios%');";}
        $resultadoU = $objeto->consultar($sql_u,$conexion);
    ?>
    <div class="container">
        <div class="titulo">
            <h1>ViBlio</h1>
        </div>
        <div class="Menu">
            <ul>
                <li><a onclick="showtab('usuario')"><ion-icon name="person-outline"></ion-icon>Usuarios</a></li>
                <li><a onclick="showtab('libros')"><ion-icon name="library-outline"></ion-icon>Libros</a></li>
                <li><a onclick="showtab('prestamos')"><ion-icon name="pricetag-outline"></ion-icon>Prestamos</a></li>
                <li><a onclick="showtab('reservas')"><ion-icon name="bookmark-outline"></ion-icon>Reservas</a></li>
                <li><a onclick="showtab('multas')"><ion-icon name="warning-outline"></ion-icon>Multas</a></li>
            </ul>
        </div>
    </div>
    <div class="main_content">
        <div id="usuario" class="tab_content">
            <div class="encabezado">
                <h2>Gestion de Usuarios</h2>
                <button onclick="abrirModal('modalAgregarUsuario')" class="btn-agregar">
                    <ion-icon name="add-circle-outline"></ion-icon> Agregar Usuario
                </button>
            </div>
            <div id="modalAgregarUsuario" class="modal">
                <div class="modal-contenido">
                    <span class="cerrar" onclick="cerrarModal('modalAgregarUsuario')">&times;</span>
                    <h2>Agregar Nuevo Usuario</h2>
                    <form action="procesar_registro.php" method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" name="nombre" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Apellido:</label>
                                <input type="text" name="apellido" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Fecha de nacimiento:</label>
                                <input type="date" name="fecha_nacimiento" required>
                            </div>

                            <div class="form-group">
                                <label>DNI:</label>
                                <input type="number" name="dni" required>
                            </div>
                            
                            <div class="form-group">
                                <label>E-mail:</label>
                                <input type="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Contraseña:</label>
                                <input type="password" name="contrasenia" required>
                            </div>
                            
                        </div>
                    
                        <div class="form-buttons">
                            <button type="submit" class="btn-guardar">Guardar</button>
                            <button type="button" onclick="cerrarModal('modalAgregarUsuario')" class="btn-cancelar">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="content">
                <h3>Usuarios</h3>
                <button class="btn-excel" onclick="exportCSVExcel_U()">
                    <ion-icon name="reader-outline"></ion-icon>Exportar a Excel</button>
                <div class="box">
                    <form method="GET">
                        <input type="text" name="busqueda_usuarios" placeholder="Buscar..." values="<?php= $busqueda ?>">
                        <button><ion-icon name="search"></ion-icon></button>
                        <button type="submit" name="todos_usuarios">Todos</button>
                    </form>
                </div>
                <div id="id_usuario" class="tabla">
                    <table >
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Fecha de nacimiento</th>
                                <th>E-mail</th>
                                <th>Rol</th>
                                <th>Nro de Prestamos</th>
                                <th>Nro de Multas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($resultadoU as $fila){
                            ?>
                            <tr>
                                <td><?php echo $fila ["dni"]?></td>
                                <td><?Php echo $fila ["nombre"]?></td>
                                <td><?Php echo $fila ["apellido"]?></td>
                                <td><?Php echo $fila ["fecha_nacimiento"]?></td>
                                <td><?Php echo $fila ["email"]?></td>
                                <td><?Php echo $fila ["rol"]?></td>
                                <td><?Php echo $fila ["numero_prestamos"]?></td>
                                <td><?Php echo $fila ["numero_multas"]?></td>
                                <td class="acciones">
                                    <?php if (!mismoUsuario($id_usuario_logueado, (int)$fila["id_usuarrio"]));?>
                                    <button class="btn-modificar" onclick="modificarUsuario('<?php echo $fila['dni']?>')" title="Modificar">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </button>
                                    <button class="btn-eliminar" onclick="eliminarUsuario('<?php echo $fila['dni']?>')" title="Eliminar">
                                        <ion-icon name="trash-outline"></ion-icon> 
                                    </button>
                                    <?php else: ?>
                                        <span title="No podes editar tu propio usuario">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="libros" class="tab_content">
            <div class="encabezado">
                <h2>Gestion de Libros</h2>
                <button onclick="abrirModal('modalAgregarLibro')" class="btn-agregar">
                    <ion-icon name="add-circle-outline"></ion-icon> Agregar Libro
                </button>
            </div>
            <div id="modalAgregarLibro" class="modal">
                <div class="modal-contenido">
                    <span class="cerrar" onclick="cerrarModal('modalAgregarLibro')">&times;</span>
                    <h2>Agregar Nuevo Libro</h2>
                    <form action="procesar_libro.php" method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>ISBN:</label>
                                <input type="text" name="isbn" placeholder="Ej: 978061" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Título:</label>
                                <input type="text" name="titulo" placeholder="Título del libro" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Edición:</label>
                                <input type="text" name="edicion" placeholder="1ra edición">
                            </div>
                            
                            <div class="form-group">
                                <label>Año:</label>
                                <input type="number" name="anio" placeholder="2024" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Autor:</label>
                                <input type="text" name="autor" placeholder="Fulanito">
                            </div>
                            
                            <div class="form-group">
                                <label>Editorial:</label>
                                <input type="text" name="editorial" placeholder="...">
                            </div>
                            
                            <div class="form-group">
                                <label>Categoría:</label>
                                <input type="text" name="categoria" placeholder="...">
                            </div>
                            
                            <div class="form-group">
                                <label>Género:</label>
                                <input type="text" name="genero" placeholder="...">
                            </div>
                        </div>
                    
                        <div class="form-buttons">
                            <button type="submit" class="btn-guardar">Guardar</button>
                            <button type="button" onclick="cerrarModal('modalAgregarLibro')" class="btn-cancelar">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="content">
                <h3>Libros</h3>
                <button class="btn-excel" onclick="exportCSVExcel()">
                    <ion-icon name="reader-outline"></ion-icon>Exportar a Excel
                </button>
                <div class="box">
                    <form method="GET">
                        <input type="text" name="busqueda" placeholder="Buscar..." values="<?php= $busqueda ?>">
                        <button><ion-icon name="search"></ion-icon></button>
                        <button type="submit" name="todos">Todos</button>
                    </form>
                </div>
                <div id="idtabla" class="tabla">
                    <table >
                        <thead>
                            <tr>
                                <th>ISBN</th>
                                <th>Titulo</th>
                                <th>Edicion</th>
                                <th>Autor</th>
                                <th>Editorial</th>
                                <th>Categoria</th>
                                <th>Genero</th>
                                <th>Año</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($resultado as $fila){
                            ?>
                            <tr>
                                <td><?Php echo $fila ["isbn"]?></td>
                                <td><?Php echo $fila ["titulo"]?></td>
                                <td><?Php echo $fila ["edicion"]?></td>
                                <td><?Php echo $fila ["autor"]?></td>
                                <td><?Php echo $fila ["editorial"]?></td>
                                <td><?Php echo $fila ["categoria"]?></td>
                                <td><?Php echo $fila ["genero"]?></td>
                                <td><?Php echo $fila ["anio_publicacion"]?></td>
                                <td>
                                    <select class="select-estado" onchange="cambiarEstado('<?php echo $fila['isbn']?>', this.value)">
                                        <option value="1" <?php echo ($fila["estado"] == 1) ? 'selected' : ''; ?>>✓ Disponible</option>
                                        <option value="0" <?php echo ($fila["estado"] == 0) ? 'selected' : ''; ?>>✗ No disponible</option>
                                    </select>
                                </td>
                                <td class="acciones">
                                    <button class="btn-modificar" onclick="modificarLibro('<?php echo $fila['isbn']?>')" title="modificar">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </button>
                                    <button class="btn-eliminar" onclick="eliminarlibro('<?php echo $fila['isbn']?>')" title="eliminar">
                                        <ion-icon name="trash-outline"></ion-icon> 
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="prestamos" class="tab_content">
            <div class="encabezado">
                <h2>Gestion de Prestamos</h2>
            </div>
        </div>
        <div id="reservas" class="tab_content">
            <div class="encabezado">
                <h2>Gestion de Reservas</h2>
            </div>
        </div>
        <div id="multas" class="tab_content">
            <div class="encabezado">
                <h2>Gestion de Multas</h2>
            </div>
        </div>
    </div>
    <div id="modalModificarUsuario" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal('modalModificarUsuario')">&times;</span>
            <h2>Modificar Usuario</h2>
            <form id="formModificarUsuario" action="modificar_usuario.php" method="POST">
                <input type="hidden" id="mod_u_id_usuario" name="id_usuario">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>DNI:</label>
                        <input type="text" id="mod_u_dni" name="dni" readonly required>
                    </div>

                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" id="mod_u_nombre" name="nombre" placeholder="Nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Apellido:</label>
                        <input type="text" id="mod_u_apellido" name="apellido" placeholder="Apellido" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Fecha de Nacimiento:</label>
                        <input type="date" id="mod_u_fecha" name="fecha_nacimiento" required>
                    </div>
                    
                    <div class="form-group">
                        <label>E-mail:</label>
                        <input type="email" id="mod_u_email" name="email" placeholder="usuario@email.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Nueva Contraseña:</label>
                        <input type="password" id="mod_u_contrasenia" name="contrasenia" placeholder="Dejar vacío para no cambiar">
                        <small style="color: #888; font-size: 11px;">Solo completa si deseas cambiar la contraseña</small>
                    </div>
                </div>
            
                <div class="form-buttons">
                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
                    <button type="button" onclick="cerrarModal('modalModificarUsuario')" class="btn-cancelar">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <div id="modalmodificarLibro" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal('modalmodificarLibro')">&times;</span>
            <h2>Modificar Libro</h2>
            <form id="ModificarLibro" action="modificar_libro.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>ISBN:</label>
                        <input type="text" id="mod_isbn" name="isbn" readonly required>
                    </div>

                    <div class="form-group">
                        <label>Título:</label>
                        <input type="text" id="mod_titulo" name="titulo" placeholder="Título del libro" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Edición:</label>
                        <input type="text" id="mod_edicion" name="edicion" placeholder="1ra edición">
                    </div>
                    
                    <div class="form-group">
                        <label>Año:</label>
                        <input type="number" id="mod_anio" name="anio" placeholder="2024" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Autor:</label>
                        <input type="text" id="mod_autor" name="autor" required>
                        
                    </div>
                    
                    <div class="form-group">
                        <label>Editorial:</label>
                        <input type="text" id="mod_editorial" name="editorial" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Categoría:</label>
                        <input type="text" id="mod_categoria" name="categoria" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Género:</label>
                        <input type="text" id="mod_genero" name="genero" required>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
                    <button type="button" onclick="cerrarModal('modalmodificarLibro')" class="btn-cancelar">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script
    src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script
        src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script src="script.js"></script>
</body>
</html>