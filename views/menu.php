<?php
require_once dirname(__DIR__) . '/Core/AuthGuard.php';
require_once dirname(__DIR__) . '/Usuarios/UsuarioRepository.php';
require_once dirname(__DIR__) . '/Libros/LibroRepository.php';
require_once dirname(__DIR__) . '/Perfiles/PerfilRepository.php';
require_once __DIR__ . '/../Core/AuditoriaRepository.php';

$auditorias = (new AuditoriaRepository())->listarUltimos(50);




require_once dirname(__DIR__) . '/Prestamos/PrestamoRepository.php';
require_once dirname(__DIR__) . '/Reservas/ReservaRepository.php';

AuthGuard::verificarRol(['bibliotecario']);

$id_usuario_logueado = (int) $_SESSION["id_usuario"];

$usuarioRepo  = new UsuarioRepository();
$libroRepo    = new LibroRepository();
$perfilRepo   = new PerfilRepository();
$prestamoRepo = new PrestamoRepository();
$reservaRepo  = new ReservaRepository();

// Perfiles (para selects y tabla de roles)
$perfiles = $perfilRepo->listarTodos();

// Libros (con búsqueda segura vía prepared statements)
$busqueda     = $_GET["busqueda"] ?? "";
$mostrartodos = isset($_GET["todos"]);
$resultado    = $libroRepo->listar($busqueda, $mostrartodos);

// Usuarios (con búsqueda segura vía prepared statements)
$busqueda_usuarios = $_GET["busqueda_usuarios"] ?? "";
$todos_usuarios     = isset($_GET["todos_usuarios"]);
$resultadoU         = $usuarioRepo->listar($busqueda_usuarios, $todos_usuarios);

// Prestamos (con búsqueda segura vía prepared statements)
$busqueda_prestamos = $_GET["busqueda_prestamos"] ?? "";
$todos_prestamos     = isset($_GET["todos_prestamos"]);
$resultadoP          = $prestamoRepo->listarTodos($busqueda_prestamos, $todos_prestamos);

// Reservas (con búsqueda segura vía prepared statements)
$busqueda_reservas = $_GET["busqueda_reservas"] ?? "";
$todos_reservas     = isset($_GET["todos_reservas"]);
$resultadoR         = $reservaRepo->listarTodos($busqueda_reservas, $todos_reservas);

// Alumnos activos, para los selects de los modales de Agregar Prestamo/Reserva
$alumnosDisponibles = array_filter($resultadoU, fn($u) => $u['rol'] === 'alumno');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Gestion</title>
    <link rel="stylesheet" href="style_menu.css">
    <link rel="stylesheet" href="user_info.css">
</head>
<body>
    <div class="container">
        <div class="titulo">
            <h1>ViBlio</h1>
        </div>
        <div id="user-info" class="user-info">
            <ion-icon name="person-circle-outline"></ion-icon>
            <span id="user-name"></span>
        </div>
        <div class="Menu">
            <ul>
                <li><a onclick="showtab('usuario')"><ion-icon name="person-outline"></ion-icon>Usuarios</a></li>
                <li><a onclick="showtab('libros')"><ion-icon name="library-outline"></ion-icon>Libros</a></li>
                <li><a onclick="showtab('prestamos')"><ion-icon name="pricetag-outline"></ion-icon>Prestamos</a></li>
                <li><a onclick="showtab('reservas')"><ion-icon name="bookmark-outline"></ion-icon>Reservas</a></li>
                <li><a onclick="showtab('multas')"><ion-icon name="warning-outline"></ion-icon>Multas</a></li>
                <li><a onclick="showtab('perfiles')"><ion-icon name="shield-outline"></ion-icon>Perfiles</a></li>
                 <li><a onclick="showtab('auditoria')"><ion-icon name="newspaper-outline"></ion-icon>Auditoria</a></li>
                <li><a href="#" onclick="mdConfirm('¿Cerrar sesión?', function(){ window.location.href='logout.php'; })"><ion-icon name="log-out-outline"></ion-icon>Salir</a></li>
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
                    <form action="../controlers/procesar_registro.php" method="POST">
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

                            <div class="form-group">
                                <label>Confirmar Contraseña:</label>
                                <input type="password" name="confirmar_contrasenia" required>
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
                        <input type="text" name="busqueda_usuarios" placeholder="Buscar..." value="<?= htmlspecialchars($busqueda_usuarios) ?>">
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
                            <?php foreach ($resultadoU as $fila): ?>
                            <tr>
                                <td><?= htmlspecialchars($fila["dni"]) ?></td>
                                <td><?= htmlspecialchars($fila["nombre"]) ?></td>
                                <td><?= htmlspecialchars($fila["apellido"]) ?></td>
                                <td><?= htmlspecialchars($fila["fecha_nacimiento"]) ?></td>
                                <td><?= htmlspecialchars($fila["email"]) ?></td>
                                <td><?= htmlspecialchars($fila["rol"]) ?></td>
                                <td><?= htmlspecialchars((string) $fila["numero_prestamos"]) ?></td>
                                <td><?= htmlspecialchars((string) $fila["numero_multas"]) ?></td>
                                <td class="acciones">
                                    <?php if (!AuthGuard::esElMismoUsuario($id_usuario_logueado, (int) $fila["id_usuario"])): ?>
                                    <button class="btn-modificar" onclick="modificarUsuario('<?= htmlspecialchars($fila['dni']) ?>')" title="Modificar">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </button>
                                    <button class="btn-eliminar" onclick="eliminarUsuario('<?= htmlspecialchars($fila['dni']) ?>')" title="Eliminar">
                                        <ion-icon name="trash-outline"></ion-icon> 
                                    </button>
                                    <?php else: ?>
                                        <span title="No podes editar tu propio usuario">-</span>
                                    <?php endif; ?>
                                    <button class="btn-accion" style="background:#10B981;color:white;" 
                                            onclick="gestionarModulos(<?= (int) $fila['id_usuario'] ?>, '<?= htmlspecialchars($fila['nombre']) ?>')">
                                        <ion-icon name="apps-outline"></ion-icon>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
                    <form action="../controlers/procesar_libro.php" method="POST">
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
                        <input type="text" name="busqueda" placeholder="Buscar..." value="<?= htmlspecialchars($busqueda) ?>">
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
                                    <select class="select-estado" onchange="cambiarEstado('<?= htmlspecialchars($fila['isbn']) ?>', this.value)">
                                        <option value="1" <?= ($fila["estado"] == 1) ? 'selected' : ''; ?>>✓ Disponible</option>
                                        <option value="0" <?= ($fila["estado"] == 0) ? 'selected' : ''; ?>>✗ No disponible</option>
                                    </select>
                                </td>
                                <td class="acciones">
                                    <button class="btn-modificar" onclick="modificarLibro('<?= htmlspecialchars($fila['isbn']) ?>')" title="modificar">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </button>
                                    <button class="btn-eliminar" onclick="eliminarlibro('<?= htmlspecialchars($fila['isbn']) ?>')" title="eliminar">
                                        <ion-icon name="trash-outline"></ion-icon> 
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




        <div id="prestamos" class="tab_content">
            <div class="encabezado">
                <h2>Gestion de Prestamos</h2>
                <button onclick="abrirModal('modalAgregarPrestamo')" class="btn-agregar">
                    <ion-icon name="add-circle-outline"></ion-icon> Registrar Prestamo
                </button>
            </div>
            <div id="modalAgregarPrestamo" class="modal">
                <div class="modal-contenido">
                    <span class="cerrar" onclick="cerrarModal('modalAgregarPrestamo')">&times;</span>
                    <h2>Registrar Nuevo Prestamo</h2>
                    <form action="../controlers/procesar_prestamo.php" method="POST">
                        <input type="hidden" name="accion" value="registrar">
                        <div class="form-grid" style="grid-template-columns: 1fr;">
                            <div class="form-group">
                                <label>Alumno:</label>
                                <select name="id_usuario" required>
                                    <option value="">Seleccionar alumno...</option>
                                    <?php foreach ($alumnosDisponibles as $a): ?>
                                    <option value="<?= (int) $a['id_usuario'] ?>">
                                        <?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido'] . ' (DNI ' . $a['dni'] . ')') ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Libro:</label>
                                <select name="id_libro" required>
                                    <option value="">Seleccionar libro...</option>
                                    <?php foreach ($resultado as $l): ?>
                                        <?php if ($l['estado'] == 1): ?>
                                        <option value="<?= (int) $l['id_libro'] ?>">
                                            <?= htmlspecialchars($l['titulo'] . ' (ISBN ' . $l['isbn'] . ')') ?>
                                        </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="btn-guardar">Registrar</button>
                            <button type="button" onclick="cerrarModal('modalAgregarPrestamo')" class="btn-cancelar">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="content">
                <h3>Prestamos</h3>
                <div class="box">
                    <form method="GET">
                        <input type="text" name="busqueda_prestamos" placeholder="Buscar por libro, alumno o DNI..." value="<?= htmlspecialchars($busqueda_prestamos) ?>">
                        <button><ion-icon name="search"></ion-icon></button>
                        <button type="submit" name="todos_prestamos">Todos</button>
                    </form>
                </div>
                <div class="tabla">
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Libro</th>
                                <th>Alumno</th>
                                <th>DNI</th>
                                <th>Fecha Prestamo</th>
                                <th>Vencimiento</th>
                                <th>Devolución</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultadoP as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) $p['codigo_prestamo']) ?></td>
                                <td><?= htmlspecialchars($p['titulo']) ?></td>
                                <td><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></td>
                                <td><?= htmlspecialchars($p['dni']) ?></td>
                                <td><?= htmlspecialchars($p['fecha_prestamo']) ?></td>
                                <td><?= htmlspecialchars($p['fecha_vencimieto']) ?></td>
                                <td><?= $p['fecha_devolucion'] ? htmlspecialchars($p['fecha_devolucion']) : '-' ?></td>
                                <td><?= htmlspecialchars($p['estado_descripcion']) ?></td>
                                <td class="acciones">
                                    <?php if ($p['estado_descripcion'] === 'activo'): ?>
                                    <form action="../controlers/procesar_prestamo.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="accion" value="devolver">
                                        <input type="hidden" name="id_prestamo" value="<?= (int) $p['id_prestamo'] ?>">
                                        <button type="submit" class="btn-modificar" title="Registrar devolución">
                                            <ion-icon name="checkmark-done-outline"></ion-icon>
                                        </button>
                                    </form>
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




        <div id="reservas" class="tab_content">
            <div class="encabezado">
                <h2>Gestion de Reservas</h2>
                <button onclick="abrirModal('modalAgregarReserva')" class="btn-agregar">
                    <ion-icon name="add-circle-outline"></ion-icon> Registrar Reserva
                </button>
            </div>
            <div id="modalAgregarReserva" class="modal">
                <div class="modal-contenido">
                    <span class="cerrar" onclick="cerrarModal('modalAgregarReserva')">&times;</span>
                    <h2>Registrar Nueva Reserva</h2>
                    <form action="../controlers/procesar_reserva.php" method="POST">
                        <input type="hidden" name="accion" value="registrar">
                        <div class="form-grid" style="grid-template-columns: 1fr;">
                            <div class="form-group">
                                <label>Alumno:</label>
                                <select name="id_usuario" required>
                                    <option value="">Seleccionar alumno...</option>
                                    <?php foreach ($alumnosDisponibles as $a): ?>
                                    <option value="<?= (int) $a['id_usuario'] ?>">
                                        <?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido'] . ' (DNI ' . $a['dni'] . ')') ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Libro:</label>
                                <select name="id_libro" required>
                                    <option value="">Seleccionar libro...</option>
                                    <?php foreach ($resultado as $l): ?>
                                        <?php if ($l['estado'] == 1): ?>
                                        <option value="<?= (int) $l['id_libro'] ?>">
                                            <?= htmlspecialchars($l['titulo'] . ' (ISBN ' . $l['isbn'] . ')') ?>
                                        </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <small style="color:#888;font-size:11px;">Solo se puede reservar un libro que no esté prestado.</small>
                            </div>
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="btn-guardar">Registrar</button>
                            <button type="button" onclick="cerrarModal('modalAgregarReserva')" class="btn-cancelar">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="content">
                <h3>Reservas</h3>
                <div class="box">
                    <form method="GET">
                        <input type="text" name="busqueda_reservas" placeholder="Buscar por libro, alumno o DNI..." value="<?= htmlspecialchars($busqueda_reservas) ?>">
                        <button><ion-icon name="search"></ion-icon></button>
                        <button type="submit" name="todos_reservas">Todos</button>
                    </form>
                </div>
                <div class="tabla">
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Libro</th>
                                <th>Alumno</th>
                                <th>DNI</th>
                                <th>Fecha Solicitud</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultadoR as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) $r['codigo_reserva']) ?></td>
                                <td><?= htmlspecialchars($r['titulo']) ?></td>
                                <td><?= htmlspecialchars($r['nombre'] . ' ' . $r['apellido']) ?></td>
                                <td><?= htmlspecialchars($r['dni']) ?></td>
                                <td><?= htmlspecialchars($r['fecha_solicitud']) ?></td>
                                <td><?= htmlspecialchars($r['estado_descripcion']) ?></td>
                                <td class="acciones">
                                    <?php if ($r['estado_descripcion'] === 'pendiente'): ?>
                                    <form action="../controlers/procesar_reserva.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="accion" value="cancelar">
                                        <input type="hidden" name="id_reserva" value="<?= (int) $r['id_reserva'] ?>">
                                        <button type="submit" class="btn-eliminar" title="Cancelar reserva">
                                            <ion-icon name="close-circle-outline"></ion-icon>
                                        </button>
                                    </form>
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




        <div id="multas" class="tab_content">
            <div class="encabezado">
                <h2>Gestion de Multas</h2>
            </div>
        </div>





        <div id="perfiles" class="tab_content">
            <div class="encabezado">
                <h2>Gestión de Perfiles</h2>
                <button onclick="abrirModal('modalAgregarPerfil')" class="btn-agregar">
                    <ion-icon name="add-circle-outline"></ion-icon> Agregar Perfil
                </button>
            </div>
        
            <div id="modalAgregarPerfil" class="modal">
                <div class="modal-contenido">
                    <span class="cerrar" onclick="cerrarModal('modalAgregarPerfil')">&times;</span>
                    <h2>Agregar Nuevo Perfil</h2>
                    <form action="../controlers/procesar_perfil.php" method="POST">
                        <input type="hidden" name="accion" value="agregar">
                        <div class="form-grid" style="grid-template-columns: 1fr;">
                            <div class="form-group">
                                <label>Nombre del perfil / rol:</label>
                                <input type="text" name="tipo_perfil" placeholder="Ej: docente, admin..." required
                                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                    title="Solo letras y espacios">
                            </div>
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="btn-guardar">Guardar</button>
                            <button type="button" onclick="cerrarModal('modalAgregarPerfil')" class="btn-cancelar">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        
            <div id="modalModificarPerfil" class="modal">
                <div class="modal-contenido">
                    <span class="cerrar" onclick="cerrarModal('modalModificarPerfil')">&times;</span>
                    <h2 style="border-bottom-color:#2196F3;color:#2196F3;">Modificar Perfil</h2>
                    <form action="../controlers/procesar_perfil.php" method="POST">
                        <input type="hidden" name="accion" value="modificar">
                        <input type="hidden" id="mod_perfil_id" name="id_perfil">
                        <div class="form-grid" style="grid-template-columns: 1fr;">
                            <div class="form-group">
                                <label>Nombre del perfil / rol:</label>
                                <input type="text" id="mod_perfil_nombre" name="tipo_perfil" required
                                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                    title="Solo letras y espacios">
                            </div>
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="btn-guardar">Guardar Cambios</button>
                            <button type="button" onclick="cerrarModal('modalModificarPerfil')" class="btn-cancelar">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        






            <div class="content">
                <h3>Perfiles / Roles</h3>
                <div class="tabla">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre del perfil</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($perfiles as $p): ?>
                            <tr class="fila-libro">
                                <td><?= (int) $p['id_perfil'] ?></td>
                                <td><?= htmlspecialchars($p['tipo_perfil']) ?></td>
                                <td>
                                    <?php if ($p['activo']): ?>
                                        <span class="badge badge-disponible">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-no-disponible">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="acciones">
                                    <button class="btn-accion btn-editar"
                                            onclick="modificarPerfil(<?= (int) $p['id_perfil'] ?>, '<?= htmlspecialchars($p['tipo_perfil'], ENT_QUOTES) ?>')"
                                            title="Modificar">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </button>
                                    <button class="btn-accion btn-eliminar"
                                            onclick="eliminarPerfil(<?= (int) $p['id_perfil'] ?>, '<?= htmlspecialchars($p['tipo_perfil'], ENT_QUOTES) ?>')"
                                            title="Eliminar (baja lógica)">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                    <button class="btn-accion" style="background:#10B981;color:white;"
                                            onclick="gestionarModulosPerfil(<?= (int) $p['id_perfil'] ?>, '<?= htmlspecialchars($p['tipo_perfil'], ENT_QUOTES) ?>')"
                                            title="Gestionar módulos del perfil">
                                        <ion-icon name="apps-outline"></ion-icon>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="auditoria" class="tab_content">
            <div class="encabezado">
                <h2>Historial de Auditoría</h2>
            </div>
            <div class="content">
                <h3>Últimas acciones registradas</h3>
                <div class="tabla">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $contador = 1; ?>
                            <?php foreach ($auditorias as $a): ?>
                            <tr class="filaAuditoria">
                                <td><?= $contador++ ?></td>
                                <td><?= htmlspecialchars($a['email'] ?? 'Sistema') ?></td>
                                <td><?= htmlspecialchars($a['accion']) ?></td>
                                <td><?= htmlspecialchars($a['fecha']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($auditorias)): ?>
                            <tr>
                                <td colspan="4" class="empty-state">No hay registros de auditoría todavía.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div id="modalModificarUsuario" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal('modalModificarUsuario')">&times;</span>
            <h2>Modificar Usuario</h2>
            <form id="formModificarUsuario" action="../controlers/modificar_usuario.php" method="POST">
                <input type="hidden" id="mod_u_id_usuario" name="id_usuario">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>DNI:</label>
                        <input type="text" id="mod_u_dni" name="dni" required>
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
                        <label>Rol:</label>
                        <select id="mod_u_rol" name="rol" required>
                            <?php foreach ($perfiles as $p): ?>
                                <?php if ($p['activo']): ?>
                                <option value="<?= htmlspecialchars($p['tipo_perfil']) ?>">
                                    <?= htmlspecialchars(ucfirst($p['tipo_perfil'])) ?>
                                </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nueva Contraseña:</label>
                        <input type="password" id="mod_u_contrasenia" name="contrasenia" placeholder="Dejar vacío para no cambiar">
                        <small style="color: #888; font-size: 11px;">Solo completa si deseas cambiar la contraseña</small>
                    </div>

                    <div class="form-group">
                        <label>Confirmar Nueva Contraseña:</label>
                        <input type="password" id="mod_u_confirmar" name="confirmar_contrasenia">
                        <small style="color: #888; font-size: 11px;">Solo si vas a cambiar la contraseña</small>
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
            <form id="ModificarLibro" action="../controlers/modificar_libro.php" method="POST">
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
    <!-- Modal módulos por USUARIO (existente) -->
    <div id="modalModulos" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal('modalModulos')">&times;</span>
            <h2>Módulos de <span id="nombre_usuario_mod"></span></h2>
            <div id="lista_modulos" style="display:flex;flex-direction:column;gap:15px;margin:20px 0;"></div>
            <div class="form-buttons">
                <button class="btn-guardar" onclick="guardarModulos()">Guardar</button>
                <button class="btn-cancelar" onclick="cerrarModal('modalModulos')">Cancelar</button>
            </div>
        </div>
    </div>
    <!-- Modal módulos por PERFIL -->
    <div id="modalModulosPerfil" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal('modalModulosPerfil')">&times;</span>
            <h2>Módulos del perfil: <span id="nombre_perfil_mod"></span></h2>
            <p style="color:#666;font-size:13px;margin-bottom:8px;">
                Configurá qué secciones pueden ver los usuarios con este perfil.
            </p>
            <div id="lista_modulos_perfil" style="display:flex;flex-direction:column;gap:15px;margin:20px 0;"></div>
            <div class="form-buttons">
                <button class="btn-guardar" onclick="guardarModulosPerfil()">Guardar</button>
                <button class="btn-cancelar" onclick="cerrarModal('modalModulosPerfil')">Cancelar</button>
            </div>
        </div>
    </div>









    














    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="script.js"></script>
    <script src="script_alertas.js"></script>
    <?php
        $usuarioActual = $usuarioRepo->obtenerPorId($id_usuario_logueado);
        $nombreUsuarioActual = $usuarioActual
            ? $usuarioActual['nombre'] . ' ' . $usuarioActual['apellido']
            : '';
    ?>
    <script>
        window.usuarioLogueado = {
            id: <?= $id_usuario_logueado ?>,
            nombre: "<?=htmlspecialchars($nombreUsuarioActual, ENT_QUOTES) ?>"
        }
    </script>
    <script src="cacheUsuario.js"></script>
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