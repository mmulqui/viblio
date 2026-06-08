<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style_registro.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="registro_container">

        <div class="fondo_verde">

            <a href="index.php" class="btn-volver"></a>

            <h2 class="titulo-registro">Registro</h2>

            <form action="../controlers/procesar_registro.php" method="post" class="form-registro" id="form-registro" novalidate>

                <div class="columnas">

                    <div class="columna">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="nombre">

                        <label>Apellido</label>
                        <input type="text" name="apellido" id="apellido">

                        <label>DNI</label>
                        <input type="number" name="dni" id="dni">

                        <label>Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento">
                    </div>

                    <div class="columna">

                        <label>E-mail</label>
                        <input type="email" name="email" id="email">

                        <label>Contraseña</label>
                        <input type="password" name="contrasenia" id="contrasenia">

                        <label>Confirmar Contraseña</label>
                        <input type="password" name="confirmar_contrasenia" id="confirmar_contrasenia">
                    </div>

                </div>

                <button type="submit" class="btn-registrarse">Registrarse</button>

            </form>

        </div>

    </div>

    <script src="script_alertas.js"></script>
    <script src="script_registro.js"></script>
    
</body>
</html>