<?php
require_once("../models/conexion.php");

require_once("PHPMailer/Exception.php");
require_once("PHPMailer/PHPMailer.php");
require_once("PHPMailer/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $objeto = new conexion;
    $conexion = $objeto->conectar();

    $sql = "SELECT id_usuario FROM usuario WHERE email = ?";
    $consulta = $conexion->prepare($sql);
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $id_usuario = $usuario['id_usuario'];

        // Eliminar tokens anteriores
        $sql = "DELETE FROM password_resets WHERE id_usuario = ?";
        $del = $conexion->prepare($sql);
        $del->bind_param("i", $id_usuario);
        $del->execute();

        // Generar token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar token
        $sql = "INSERT INTO password_resets (id_usuario, token, expires_at) VALUES (?, ?, ?)";
        $insert = $conexion->prepare($sql);
        $insert->bind_param("iss", $id_usuario, $token, $expires);
        $insert->execute();

        // ✅ Enviar email con PHPMailer
        $resetLink = "http://localhost/login_viblio/view_bibliotecario/views/reset_password.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tatomulqui@gmail.com';      
            $mail->Password   = 'gqph sxda bwqq luti';     
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('tuemail@gmail.com', 'ViBlio');
            $mail->addAddress($email);
            $mail->Subject = 'Recuperar contraseña - ViBlio';
            $mail->Body    = "Hola,\n\nHacé clic en el siguiente enlace para restablecer tu contraseña:\n\n$resetLink\n\nEste enlace expira en 1 hora.\n\nSi no solicitaste esto, ignorá este mensaje.";

        } catch (Exception $e) {
            error_log("Error al enviar email: " . $mail->ErrorInfo);
        }
        $mail->send();
    }

    $objeto->desconectar($conexion);
    $msg = "Si el email está registrado, recibirás un enlace de recuperación.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="container-form">
            <form class="inicio_sesion" method="POST">
                <h2>Recuperar contraseña</h2>

                <?php if ($msg): ?>
                    <p style="color:green;"><?= $msg ?></p>
                <?php endif; ?>

                <div class="container-input">
                    <input type="email" name="email" required>
                    <label>E-mail</label>
                </div>

                <input type="submit" value="Enviar enlace">
                <div class="recordar">
                    <a href="index.php">Volver al inicio de sesión</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>