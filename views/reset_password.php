<?php
require_once("../models/conexion.php");

$token = $_GET['token'] ?? '';
$error = '';

$objeto = new conexion;
$conexion = $objeto->conectar();

// Verificar token válido y no expirado
$sql = "SELECT u.id_usuario, u.email 
        FROM password_resets pr
        JOIN usuario u ON u.id_usuario = pr.id_usuario
        WHERE pr.token = ? AND pr.expires_at > NOW()";
$consulta = $conexion->prepare($sql);
$consulta->bind_param("s", $token);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows !== 1) {
    die("El enlace es inválido o ha expirado. <a href='forgot_password.php'>Solicitar uno nuevo</a>");
}

$datos = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevaPassword = $_POST['password'];
    $confirmar     = $_POST['confirmar'];

    if (strlen($nuevaPassword) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } elseif ($nuevaPassword !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $nuevaPasswordHash = password_hash($nuevaPassword, PASSWORD_BCRYPT, ["cost" => 12]);

        $sql = "UPDATE usuario SET contraseña = ? WHERE id_usuario = ?";
        $update = $conexion->prepare($sql);
        $update->bind_param("si", $nuevaPasswordHash, $datos['id_usuario']);
        $update->execute();

        // Eliminar token usado
        $sql = "DELETE FROM password_resets WHERE token = ?";
        $del = $conexion->prepare($sql);
        $del->bind_param("s", $token);
        $del->execute();

        $objeto->desconectar($conexion);

        header("Location: index.php?error=" . urlencode("Contraseña actualizada. Inicia sesión."));
        exit();
    }
}

$objeto->desconectar($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="container-form">
            <form class="inicio_sesion" method="POST">
                <h2>Nueva contraseña</h2>
                <p>Cuenta: <strong><?= htmlspecialchars($datos['email']) ?></strong></p>

                <?php if ($error): ?>
                    <p style="color:red;"><?= $error ?></p>
                <?php endif; ?>

                <div class="container-input">
                    <input type="password" name="password" required>
                    <label>Nueva contraseña</label>
                </div>
                <div class="container-input">
                    <input type="password" name="confirmar" required>
                    <label>Confirmar contraseña</label>
                </div>

                <input type="submit" value="Restablecer contraseña">
            </form>
        </div>
    </div>
</body>
</html>