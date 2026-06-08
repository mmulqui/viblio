<?php
// Si viene del registro público, no verificar rol
if (isset($_POST["registro_publico"])) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../models/conexion.php");
} else {
    require_once("../controlers/auth.php");
    verificarRol(["bibliotecario"]);
    require_once("../models/conexion.php");
}

$campos = ["nombre", "apellido", "fecha_nacimiento", "dni", "email", "contrasenia"];
if (isset($_POST["registro_publico"])) {
    $campos[] = "confirmar_contrasenia";
}

foreach ($campos as $campo) {
    if (empty($_POST[$campo])) {
        $msg = urlencode("Todos los campos son obligatorios.");
        if (isset($_POST["registro_publico"])) {
            header("Location: ../views/registro.php?error=$msg");
        } else {
            $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'Todos los campos son obligatorios.'];
            header("Location: ../views/menu.php");
        }
        exit;
    }
}

// Doble check de contraseña
if (isset($_POST["registro_publico"])) {
    if ($_POST["contrasenia"] !== $_POST["confirmar_contrasenia"]) {
        $msg = urlencode("Las contraseñas no coinciden.");
        header("Location: ../views/registro.php?error=$msg");
        exit;
    }
}

// Validar fecha de nacimiento
$hoy  = new DateTime();
$fecha = DateTime::createFromFormat("Y-m-d", $_POST["fecha_nacimiento"]);
if (!$fecha || $fecha >= $hoy) {
    $msg = urlencode("La fecha de nacimiento no puede ser futura.");
    if (isset($_POST["registro_publico"])) {
        header("Location: ../views/registro.php?error=$msg");
    } else {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'La fecha de nacimiento no puede ser futura.'];
        header("Location: ../views/menu.php");
    }
    exit;
}
$fechaParaDB = $fecha->format("Y-m-d");

$objeto   = new conexion();
$conexion = $objeto->conectar();

// Verificar email duplicado
$stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
$stmt->bind_param("s", $_POST["email"]);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $msg = urlencode("El email ya está registrado.");
    if (isset($_POST["registro_publico"])) {
        header("Location: ../views/registro.php?error=$msg");
    } else {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'El email ya está registrado.'];
        header("Location: ../views/menu.php");
    }
    exit;
}
$stmt->close();

// Verificar DNI duplicado
$stmt = $conexion->prepare("SELECT id_persona FROM persona WHERE dni = ?");
$stmt->bind_param("s", $_POST["dni"]);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $msg = urlencode("El DNI ya está registrado.");
    if (isset($_POST["registro_publico"])) {
        header("Location: ../views/registro.php?error=$msg");
    } else {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'El DNI ya está registrado.'];
        header("Location: ../views/menu.php");
    }
    exit;
}
$stmt->close();

$passwordHash = password_hash($_POST["contrasenia"], PASSWORD_BCRYPT, ["cost" => 12]);

$stmt = $conexion->prepare("CALL registrar_alumno(?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "ssssss",
    $_POST["nombre"],
    $_POST["apellido"],
    $fechaParaDB,
    $_POST["dni"],
    $_POST["email"],
    $passwordHash
);

if ($stmt->execute()) {
    if (isset($_POST["registro_publico"])) {
        header("Location: ../views/index.php?exito=" . urlencode("Registro exitoso. Ya podés iniciar sesión."));
    } else {
        $_SESSION['alerta'] = ['tipo' => 'success', 'titulo' => '¡Éxito!', 'msg' => 'Usuario registrado correctamente.'];
        header("Location: ../views/menu.php");
    }
} else {
    error_log("Error al registrar alumno: " . $stmt->error);
    if (isset($_POST["registro_publico"])) {
        header("Location: ../views/registro.php?error=" . urlencode("No se pudo registrar. Intentá de nuevo."));
    } else {
        $_SESSION['alerta'] = ['tipo' => 'error', 'titulo' => 'Error', 'msg' => 'No se pudo registrar el usuario. Intentá de nuevo.'];
        header("Location: ../views/menu.php");
    }
}
exit;
