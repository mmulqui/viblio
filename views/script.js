function showtab(tabId) {
    const tabs = document.querySelectorAll('.tab_content');
    tabs.forEach(tab => tab.style.display = 'none');
    const selected = document.getElementById(tabId);
    selected.style.display = 'block';
}
        
window.onload = () => showtab('usuario');

function abrirModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

function modificarLibro(isbn) {
    fetch('../controlers/obtener_libro.php?isbn=' + isbn)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('mod_isbn').value = data.libro.isbn;
                document.getElementById('mod_titulo').value = data.libro.titulo;
                document.getElementById('mod_edicion').value = data.libro.edicion || '';
                document.getElementById('mod_anio').value = data.libro.anio_publicacion;
                document.getElementById('mod_autor').value = data.libro.autor;
                document.getElementById('mod_editorial').value = data.libro.editorial;
                document.getElementById('mod_categoria').value = data.libro.categoria;
                document.getElementById('mod_genero').value = data.libro.genero;
                abrirModal('modalmodificarLibro');
            } else {
                mdAlert('error', 'Error', 'Error al cargar los datos del libro.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mdAlert('error', 'Error', 'Error al obtener los datos del libro.');
        });
}

function modificarUsuario(dni) {
    fetch('../controlers/obtener_usuario.php?dni=' + dni)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('mod_u_id_usuario').value = data.usuario.id_usuario;
                document.getElementById('mod_u_dni').value = data.usuario.dni;
                document.getElementById('mod_u_nombre').value = data.usuario.nombre;
                document.getElementById('mod_u_apellido').value = data.usuario.apellido;
                document.getElementById('mod_u_fecha').value = data.usuario.fecha_nacimiento;
                document.getElementById('mod_u_email').value = data.usuario.email;
                document.getElementById('mod_u_rol').value = data.usuario.rol;
                document.getElementById('mod_u_contrasenia').value = '';
                document.getElementById('mod_u_confirmar').value = '';
                abrirModal('modalModificarUsuario');
            } else {
                mdAlert('error', 'Error', 'Error al cargar los datos del usuario: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mdAlert('error', 'Error', 'Error al obtener los datos del usuario.');
        });
}

function eliminarlibro(isbn) {
    mdConfirm('¿Estás seguro de eliminar el libro con ISBN: ' + isbn + '?', function() {
        window.location.href = '../controlers/eliminar_libro.php?isbn=' + isbn;
    }, null, true);
}

function eliminarUsuario(dni) {
    mdConfirm('¿Estás seguro de eliminar al usuario con DNI: ' + dni + '?', function() {
        window.location.href = '../controlers/eliminar_usuario.php?dni=' + dni;
    }, null, true);
}

function exportCSVExcel() {
    $('#idtabla').table2excel({
        exclude: ".no-export",
        filename: "download.xls",
        fileext: ".xls",
        exclude_links: true,
        exclude_inputs: true
    });
}

function exportCSVExcel_U() {
    $('#id_usuario').table2excel({
        exclude: ".no-export",
        filename: "download.xls",
        fileext: ".xls",
        exclude_links: true,
        exclude_inputs: true
    });
}

function cambiarEstado(isbn, nuevoEstado) {
    mdConfirm('¿Deseas cambiar el estado de este libro?', function() {
        fetch('cambiar_estado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'isbn=' + isbn + '&estado=' + nuevoEstado
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mdAlert('success', '¡Éxito!', 'Estado actualizado correctamente.');
            } else {
                mdAlert('error', 'Error', 'Error al actualizar el estado: ' + data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mdAlert('error', 'Error', 'Error al actualizar el estado.');
            location.reload();
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#modalAgregarUsuario form').addEventListener('submit', function(e) {
        const pass    = this.querySelector('input[name="contrasenia"]').value;
        const confirm = this.querySelector('input[name="confirmar_contrasenia"]').value;
        if (pass !== confirm) {
            e.preventDefault();
            mdAlert('warning', 'Ups', 'Las contraseñas no coinciden.');
        }
    });

    document.getElementById('formModificarUsuario').addEventListener('submit', function(e) {
        const pass    = document.getElementById('mod_u_contrasenia').value;
        const confirm = document.getElementById('mod_u_confirmar').value;
        if (pass === '' && confirm === '') return;
        if (pass !== confirm) {
            e.preventDefault();
            mdAlert('warning', 'Ups', 'Las contraseñas no coinciden.');
        }
    });
});

let idUsuarioModulos = null;

function gestionarModulos(id_usuario, nombre) {
    idUsuarioModulos = id_usuario;
    document.getElementById('nombre_usuario_mod').textContent = nombre;

    fetch('../controlers/obtener_modulos.php?id_usuario=' + id_usuario)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const lista = document.getElementById('lista_modulos');
                lista.innerHTML = '';
                data.modulos.forEach(m => {
                    lista.innerHTML += `
                        <label style="display:flex;align-items:center;gap:12px;font-size:16px;cursor:pointer;">
                            <input type="checkbox" value="${m.id_modulo}" ${m.activo == 1 ? 'checked' : ''}
                                   style="width:18px;height:18px;accent-color:#10B981;">
                            ${m.nombre}
                        </label>`;
                });
                abrirModal('modalModulos');
            } else {
                mdAlert('error', 'Error', 'Error al cargar módulos.');
            }
        });
}

function guardarModulos() {
    const checkboxes = document.querySelectorAll('#lista_modulos input[type=checkbox]');
    const formData = new FormData();
    formData.append('id_usuario', idUsuarioModulos);
    checkboxes.forEach(cb => {
        if (cb.checked) formData.append('modulos[]', cb.value);
    });

    fetch('../controlers/guardar_modulos.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                mdAlert('success', '¡Éxito!', 'Módulos actualizados correctamente.');
                cerrarModal('modalModulos');
            } else {
                mdAlert('error', 'Error', 'Error: ' + data.message);
            }
        });
}

function modificarPerfil(id, nombre) {
    document.getElementById('mod_perfil_id').value    = id;
    document.getElementById('mod_perfil_nombre').value = nombre;
    abrirModal('modalModificarPerfil');
}
 
// Confirma y envía baja lógica
function eliminarPerfil(id, nombre) {
    mdConfirm(
        `¿Eliminar el perfil "<strong>${nombre}</strong>"?<br><small>Se hará una baja lógica; los usuarios con ese perfil no serán afectados de inmediato.</small>`,
        function () {
            fetch('../controlers/procesar_perfil.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `accion=eliminar&id_perfil=${id}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    mdAlert('success', 'Listo', data.msg);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mdAlert('error', 'Error', data.msg);
                }
            })
            .catch(() => mdAlert('error', 'Error', 'No se pudo conectar con el servidor.'));
        }
    );
}