<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="container-form">
            <!-- novalidate: desactiva alertas nativas del browser -->
            <form class="inicio_sesion" method="post" action="../controlers/procesar.php" id="form-login" novalidate>
                <h2>Iniciar Sesion</h2>
                <div class="container-input">
                    <input type="text" name="email" id="login-email" required>
                    <label>E-mail</label>
                </div>
                <div class="container-input">
                    <input type="password" name="contrasenia" id="login-contrasenia" required>
                    <label>Contraseña</label>
                </div>
                <input type="submit" value="Iniciar">
                <div class="recordar">¿Has olvidado tu Contraseña?
                    <a href="../views/forgot_password.php">Recordarme</a>
                </div>
            </form>
        </div>
        <div class="container-form-2">
            <form class="registro" method="post">
                <h1>ViBlio</h1>
                <div class="texto_registro">¿Aún no te has registrado?</div>
                <input type="button" value="Registrarse" onclick="window.location.href='registro.php';">
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        // Mostrar alertas SweetAlert2 en lugar de párrafos planos
        <?php if (isset($_GET['exito'])): ?>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: <?= json_encode(htmlspecialchars($_GET['exito'])) ?>,
            confirmButtonColor: '#2e7d32'
        });
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: <?= json_encode(htmlspecialchars($_GET['error'])) ?>,
            confirmButtonColor: '#c62828'
        });
        <?php endif; ?>

        document.getElementById('form-login').addEventListener('submit', function (e) {
            e.preventDefault();

            const email      = document.getElementById('login-email').value.trim();
            const contrasenia = document.getElementById('login-contrasenia').value;

            if (!email || !contrasenia) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Completá el e-mail y la contraseña para continuar.',
                    confirmButtonColor: '#2e7d32'
                });
                return;
            }

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

            this.submit();
        });
    </script>
</body>
</html>