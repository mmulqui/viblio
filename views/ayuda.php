<?php
// Pestaña "Ayuda" del menú del bibliotecario.
// Se incluye desde menu.php dentro de <div class="main_content">.
require_once dirname(__DIR__) . '/Multas/MultaService.php';
$diasDanio = MultaService::DIAS_POR_DANIO;
?>
<div id="ayuda" class="tab_content">
    <div class="encabezado">
        <h2>Ayuda</h2>
    </div>
    <div class="content">

        <div class="ayuda-reglas">
            <h3>Reglas del sistema</h3>
            <ul>
                <li>Un préstamo dura una semana. La fecha de vencimiento se calcula al registrarlo.</li>
                <li>Un alumno puede tener hasta 3 préstamos activos al mismo tiempo. Un profesor puede tener todos los que necesite, sin límite.</li>
                <li>Un libro que ya está prestado no se puede reservar.</li>
                <li>Cuando el stock de un libro llega a 0, el libro figura como "No disponible".</li>
                <li>Una devolución tardía, o con el libro rayado, maltratado o roto, genera una sanción por tiempo: mientras dura, el usuario no puede pedir libros ni hacer reservas.</li>
            </ul>
        </div>

        <div class="ayuda-lista">

            <details>
                <summary><ion-icon name="person-outline"></ion-icon> Usuarios</summary>
                <div class="ayuda-cuerpo">
                    <p><strong>Agregar un usuario:</strong> tocá "Agregar Usuario", completá los datos y repetí la contraseña en "Confirmar Contraseña".</p>
                    <p><strong>Buscar:</strong> escribí en el cuadro de búsqueda y tocá la lupa. El botón "Todos" muestra el listado completo.</p>
                    <p><strong>Modificar:</strong> usá el lápiz de la fila. Si dejás la contraseña vacía, se mantiene la actual.</p>
                    <p><strong>Eliminar:</strong> usá el tacho de la fila. Tu propio usuario no se puede modificar ni eliminar: en su fila aparece un guion.</p>
                    <p><strong>Módulos de un usuario:</strong> el botón verde de la fila abre las secciones que ese usuario puede ver.</p>
                    <p><strong>Exportar:</strong> "Exportar a Excel" descarga la tabla tal como la ves.</p>
                </div>
            </details>

            <details>
                <summary><ion-icon name="library-outline"></ion-icon> Libros y stock</summary>
                <div class="ayuda-cuerpo">
                    <p><strong>Agregar un libro:</strong> tocá "Agregar Libro" y completá ISBN, título, año y stock. El resto de los datos es opcional al crearlo.</p>
                    <p><strong>Stock:</strong> es la cantidad de ejemplares que tiene la biblioteca. Si llega a 0, el libro se muestra como "(No disponible)".</p>
                    <p><strong>Agregar stock:</strong> cuando llegan ejemplares nuevos, tocá "Agregar Stock", elegí el libro, indicá cuántos ejemplares ingresan y, si querés, de dónde vienen (compra, donación, etc.). Esos ejemplares se suman al stock actual y la procedencia queda registrada en la Auditoría. Si el libro estaba sin stock, vuelve a figurar como disponible.</p>
                    <p><strong>Modificar:</strong> cambia los datos del libro (título, autor, editorial, etc.), pero no el stock: para eso está "Agregar Stock". El ISBN no se puede cambiar. Para corregirlo, eliminá el libro y volvé a cargarlo.</p>
                    <p><strong>Buscar y exportar:</strong> funcionan igual que en Usuarios.</p>
                </div>
            </details>

            <details>
                <summary><ion-icon name="pricetag-outline"></ion-icon> Préstamos</summary>
                <div class="ayuda-cuerpo">
                    <p><strong>Registrar un préstamo:</strong> tocá "Registrar Prestamo", elegí el usuario (alumno o profesor) y el libro, y confirmá con "Registrar". Solo aparecen los libros disponibles.</p>
                    <p><strong>Devolver un libro:</strong> en la fila del préstamo activo, tocá el botón con el doble tilde. Se abre una ventana donde indicás en qué estado volvió el libro (buen estado, rayado, maltratado o roto) y, si querés, una observación. Al confirmar queda guardada la fecha de devolución y, si corresponde, se generan las sanciones (ver "Multas").</p>
                    <p><strong>Si no te deja registrar:</strong> revisá que el libro tenga stock, que el usuario no tenga una sanción vigente (pestaña Multas) y, si es un alumno, que no tenga ya 3 préstamos activos. Los profesores no tienen límite de préstamos.</p>
                    <p><strong>Buscar:</strong> podés filtrar por libro, usuario o DNI. La columna "Rol" de la tabla indica si el préstamo es de un alumno o de un profesor.</p>
                </div>
            </details>

            <details>
                <summary><ion-icon name="bookmark-outline"></ion-icon> Reservas</summary>
                <div class="ayuda-cuerpo">
                    <p><strong>Registrar una reserva:</strong> tocá "Registrar Reserva", elegí el usuario y el libro, y confirmá. Solo se puede reservar un libro que no esté prestado.</p>
                    <p><strong>Cancelar una reserva:</strong> disponible solo para reservas en estado "pendiente". Usá el botón de la cruz en la fila.</p>
                    <p><strong>Si no te deja registrar:</strong> un usuario con una sanción vigente no puede reservar hasta que termine.</p>
                    <p><strong>Buscar:</strong> podés filtrar por libro, alumno o DNI.</p>
                </div>
            </details>

            <details>
                <summary><ion-icon name="warning-outline"></ion-icon> Multas</summary>
                <div class="ayuda-cuerpo">
                    <p>Las multas son <strong>sanciones por tiempo</strong>: mientras una sanción está vigente, el usuario (alumno o profesor) no puede pedir libros ni hacer reservas.</p>
                    <p><strong>Cuándo se generan:</strong> al registrar una devolución desde la pestaña Préstamos.</p>
                    <p><strong>Por retraso:</strong> <?= MultaService::DIAS_SANCION_POR_DIA_DE_RETRASO ?> día(s) de sanción por cada día de retraso. Devolver el mismo día del vencimiento no es retraso.</p>
                    <p><strong>Por mal estado:</strong> rayado, <?= $diasDanio['rayado'] ?> días; maltratado, <?= $diasDanio['maltratado'] ?> días; roto, <?= $diasDanio['roto'] ?> días.</p>
                    <p><strong>Varias sanciones:</strong> si la devolución es tardía y además el libro vuelve dañado, se generan dos sanciones. Si el usuario ya tenía una vigente, la nueva empieza cuando termina la anterior.</p>
                    <p><strong>Estados:</strong> "vigente" (todavía corre), "cumplida" (ya terminó) y "anulada".</p>
                    <p><strong>Anular:</strong> en la fila de una sanción vigente, usá el botón de la cruz. Sirve, por ejemplo, si se cargó el estado del libro por error.</p>
                    <p><strong>Buscar:</strong> podés filtrar por nombre, DNI, libro, motivo o estado.</p>
                </div>
            </details>

            <details>
                <summary><ion-icon name="shield-outline"></ion-icon> Perfiles y módulos</summary>
                <div class="ayuda-cuerpo">
                    <p><strong>Perfil:</strong> es el rol de un usuario (por ejemplo bibliotecario, alumno o profesor). El nombre solo puede tener letras y espacios.</p>
                    <p><strong>Eliminar un perfil:</strong> es una baja lógica. El perfil queda como "Inactivo" y deja de ofrecerse al elegir el rol de un usuario.</p>
                    <p><strong>Módulos del perfil:</strong> el botón verde define qué secciones ven todos los usuarios de ese perfil.</p>
                </div>
            </details>

            <details>
                <summary><ion-icon name="newspaper-outline"></ion-icon> Auditoría</summary>
                <div class="ayuda-cuerpo">
                    <p>Muestra las acciones registradas, de la más reciente a la más antigua: quién la hizo, en qué módulo, qué acción, el detalle y la fecha. Es solo de consulta.</p>
                    <p><strong>Filtrar:</strong> por módulo, por texto (acción, detalle o e-mail) y por rango de fechas. "Limpiar" quita los filtros.</p>
                    <p><strong>Páginas:</strong> elegí cuántas filas ver por página y navegá con los números de abajo.</p>
                </div>
            </details>

            <details>
                <summary><ion-icon name="stats-chart-outline"></ion-icon> Estadísticas</summary>
                <div class="ayuda-cuerpo">
                    <p>Hay cinco gráficos: usuarios por rol, préstamos por estado, préstamos por mes (últimos 6 meses), libros más prestados y reservas por estado.</p>
                    <p><strong>Exportar todo:</strong> los botones de arriba descargan todos los gráficos en Excel o PDF.</p>
                    <p><strong>Exportar uno:</strong> cada gráfico tiene sus propios botones de Excel y PDF.</p>
                </div>
            </details>

        </div>
    </div>
</div>