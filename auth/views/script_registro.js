document.getElementById('form-registro').addEventListener('submit', function (e) {
    e.preventDefault();

    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const dni = document.getElementById('dni').value.trim();
    const fecha_nacimiento = document.getElementById('fecha_nacimiento').value.trim();
    const email = document.getElementById('email').value.trim();
    const contrasenia = document.getElementById('contrasenia').value;
    const confirmar = document.getElementById('confirmar_contrasenia').value;

    // Campos vacíos
    if (!nombre || !apellido || !dni || !fecha_nacimiento || !email || !contrasenia || !confirmar) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor, completá todos los campos antes de continuar.',
            confirmButtonColor: '#2e7d32'
        });
        return;
    }

    // Email básico
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        Swal.fire({
            icon: 'error',
            title: 'E-mail inválido',
            text: 'Ingresá una dirección de correo válida.',
            confirmButtonColor: '#2e7d32'
        });
        return;
    }

    // Contraseñas coinciden
    if (contrasenia !== confirmar) {
        Swal.fire({
            icon: 'error',
            title: 'Las contraseñas no coinciden',
            text: 'Verificá que ambas contraseñas sean iguales.',
            confirmButtonColor: '#c62828'
        });
        return;
    }

    this.submit();
});