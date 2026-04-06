<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="style.css">
 </head>
<body>
    <?php

    ?>
    <div class="container">
        <div class="container-form">
            <form class="inicio_sesion" method="post" action="../controlers/procesar.php">
                <h2>Iniciar Sesion</h2>
                <div class="container-input">
                    <input type="text" name="email" required>
                    <label>E-mail</label>
                </div>
                <div class="container-input">
                    <input type="password" name="contrasenia" required>
                    <label>Contraseña</label>
                </div>
                <input type="submit" value="Iniciar">
                    <div class="recordar">¿Has olvidado tu Contraseña?
                        <a href="../views/forgot_password.php">Recordarme</a>
                    </div>
                    <?php if (isset($_GET["error"])): ?>
                        <p style="color:red;"><?php echo $_GET["error"]; ?></p>
                    <?php endif; ?>
            </form>
        </div>
        <div class="container-form-2">
            <form class="registro" method="post">
                <h1>ViBlio</h1>
                <div class="texto_registro">¿Aún no te has registrado?</div>
                <input type="button" value="Registrarse" onclick="window.location.href='registro.html';">
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>