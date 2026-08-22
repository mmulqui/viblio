document.addEventListener('DOMContentLoaded', function() {
    var btnAnadir = document.getElementById('btn-añadir');
    
    // Nos aseguramos de que el botón exista en la página actual antes de escuchar el clic
    if (btnAnadir) {
        btnAnadir.addEventListener('click', function(event) {
            // 1. Frenamos el envío automático del formulario
            event.preventDefault(); 

            // 2. Capturamos los elementos del HTML
            var formulario = document.getElementById('form-carrito');
            var idProducto = document.getElementById('producto-id').value;
            var alertaStock = document.getElementById('alerta-stock');

            // 3. Obtenemos la URL base desde el action del formulario de forma dinámica
            // Esto transforma "/viblio/controlers/CarritoController.php" en lo que necesitamos
            var urlControlador = formulario.getAttribute('action');

            // 4. Construimos la URL de validación pasándole los parámetros por la URL (?accion=...)
            var urlValidacion = urlControlador + '?accion=validar_disponibilidad&id=' + idProducto + '&cantidad=1';

            // 5. Hacemos la consulta al controlador en segundo plano (AJAX)
            fetch(urlValidacion)
                .then(function(respuesta) {
                    return respuesta.text();
                })
                .then(function(resultado) {
                    // 6. Evaluamos la respuesta del PHP
                    if (resultado.trim() === 'disponible') {
                        formulario.submit(); // Todo OK, procesamos el formulario
                    } else {
                        // Error, mostramos la alerta visual
                        if (alertaStock) alertaStock.style.display = 'block';
                        btnAnadir.style.background = '#ccc';
                        btnAnadir.disabled = true;
                    }
                })
                .catch(function(error) {
                    console.error('Error al validar disponibilidad:', error);
                });
        });
    }
});