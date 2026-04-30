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
    console.log("Modificar libro llamado con ISBN:", isbn);
    
    fetch('obtener_libro.php?isbn=' + isbn)
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos:", data);
            
            if(data.success) {
                document.getElementById('mod_isbn').value = data.libro.isbn;
                document.getElementById('mod_titulo').value = data.libro.titulo;
                document.getElementById('mod_edicion').value = data.libro.edicion || '';
                document.getElementById('mod_anio').value = data.libro.anio_publicacion;
                document.getElementById('mod_autor').value = data.libro.autor;
                document.getElementById('mod_editorial').value = data.libro.editorial;
                document.getElementById('mod_categoria').value = data.libro.categoria;
                document.getElementById('mod_genero').value = data.libro.genero;
                
                console.log("Formulario llenado, abriendo modal");
                abrirModal('modalmodificarLibro');
            } else {
                alert('Error al cargar los datos del libro');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al obtener los datos del libro');
        });
}

function modificarUsuario(dni) {
    console.log("Modificar usuario llamado con DNI:", dni);
    
    fetch('obtener_usuario.php?dni=' + dni)
        .then(response => response.json())
        .then(data => {
            console.log("📦 Datos recibidos:", data);
            
            if(data.success) {
                console.log("Llenando formulario...");
                
                document.getElementById('mod_u_id_usuario').value = data.usuario.id_usuario;
                document.getElementById('mod_u_dni').value = data.usuario.dni;
                document.getElementById('mod_u_nombre').value = data.usuario.nombre;
                document.getElementById('mod_u_apellido').value = data.usuario.apellido;
                document.getElementById('mod_u_fecha').value = data.usuario.fecha_nacimiento;
                document.getElementById('mod_u_email').value = data.usuario.email;
                document.getElementById('mod_u_contrasenia').value = '';
                
                console.log("Formulario llenado, abriendo modal");
                abrirModal('modalModificarUsuario');
            } else {
                alert('Error al cargar los datos del usuario: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al obtener los datos del usuario');
        });
}


function eliminarlibro(isbn) {
    if (confirm("¿Estas seguro de eliminar el libro con ISBN: "+ isbn +"?")){
        window.location.href = "../controlers/eliminar_libro.php?isbn=" + isbn;
    }
}

function eliminarUsuario(dni) {
    if (confirm("¿Estas seguro de eliminar al Usuario con DNI: "+ dni +"?")){
        window.location.href = "../controlers/eliminar_usuario.php?dni=" + dni;
    }
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
    console.log("Cambiando estado del libro ISBN:", isbn, "a:", nuevoEstado);
    
    if(confirm('¿Deseas cambiar el estado de este libro?')) {
        fetch('cambiar_estado.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'isbn=' + isbn + '&estado=' + nuevoEstado
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Estado actualizado correctamente');
            } else {
                alert('Error al actualizar el estado: ' + data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el estado');
            location.reload();
        });
    } else {
        location.reload();
    }
}
