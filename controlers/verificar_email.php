<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("../models/conexion.php");

// Valida que llegó un token 
if (empty($_GET["token"])) {
    header("Location: ../views/index.php");
    exit;
}

$token    = trim($_GET["token"]);
$objeto   = new conexion();
$conexion = $objeto->conectar();

// Busca el registro pendiente (token válido y no expirado)
$stmt = $conexion->prepare(
    "SELECT id, nombre, apellido, fecha_nacimiento, dni, email, password_hash
     FROM registros_pendientes
     WHERE token = ? AND expira_en > NOW()"
);
$stmt->bind_param("s", $token);
$stmt->execute();
$result    = $stmt->get_result();
$pendiente = $result->fetch_assoc();
$stmt->close();

// Token inválido o expirado
if (!$pendiente) {
    header("Location: ../views/registro.php?error=" . urlencode(
        "El enlace de verificación expiró o ya fue utilizado. Podés registrarte nuevamente."
    ));
    exit;
}

$stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
$stmt->bind_param("s", $pendiente["email"]);
$stmt->execute();
$stmt->store_result();
$emailExiste = $stmt->num_rows > 0;
$stmt->close();

if ($emailExiste) {
    // La cuenta ya existe (caso borde: doble clic en el enlace)
    $del = $conexion->prepare("DELETE FROM registros_pendientes WHERE token = ?");
    $del->bind_param("s", $token);
    $del->execute();
    $del->close();

    header("Location: ../views/index.php?exito=" . urlencode(
        "Tu cuenta ya está activa. Iniciá sesión."
    ));
    exit;
}

$stmt = $conexion->prepare("CALL registrar_alumno(?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "ssssss",
    $pendiente["nombre"],
    $pendiente["apellido"],
    $pendiente["fecha_nacimiento"],
    $pendiente["dni"],
    $pendiente["email"],
    $pendiente["password_hash"]   // ya viene hasheado desde el registro
);

if ($stmt->execute()) {
    $stmt->close();

    // Elimina el registro pendiente
    $del = $conexion->prepare("DELETE FROM registros_pendientes WHERE token = ?");
    $del->bind_param("s", $token);
    $del->execute();
    $del->close();

    header("Location: ../views/index.php?exito=" . urlencode(
        "¡Cuenta verificada con éxito! Ya podés iniciar sesión."
    ));
} else {
    error_log("Error al registrar usuario desde verificar_email: " . $stmt->error);
    $stmt->close();

    header("Location: ../views/registro.php?error=" . urlencode(
        "No se pudo completar el registro. Contactá al administrador."
    ));
}
exit;