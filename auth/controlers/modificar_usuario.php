<?php
require_once("../controlers/auth.php");
verificarRol(["bibliotecario"]);
require_once("../models/conexion.php");
require_once("user_sesion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/menu.php");
    exit;
}

$id_usuario_logueado = (int) $_SESSION["id_usuario"];
$id_usuario_editar   = (int) $_POST["id_usuario"];

if (mismoUsuario($id_usuario_logueado, $id_usuario_editar)) {
    $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'No podés editar tu propio usuario.'];
    header("Location: ../views/menu.php");
    exit;
}

$objeto   = new conexion();
$conexion = $objeto->conectar();

// Validar rol
$stmtPerfil = $conexion->prepare("SELECT id_perfil FROM perfil WHERE tipo_perfil = ?");
$stmtPerfil->bind_param("s", $_POST["rol"]);
$stmtPerfil->execute();
$resPerfil = $stmtPerfil->get_result()->fetch_assoc();
$stmtPerfil->close();

if (!$resPerfil) {
    $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'No se encontró el perfil seleccionado.'];
    header("Location: ../views/menu.php");
    exit;
}
$id_perfil = $resPerfil['id_perfil'];

if (!empty($_POST["contrasenia"]) && $_POST["contrasenia"] !== $_POST["confirmar_contrasenia"]) {
    $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'Las contraseñas no coinciden.'];
    header("Location: ../views/menu.php");
    exit;
}

// Verificar DNI duplicado (excluyendo al usuario actual)
$stmtDni = $conexion->prepare(
    "SELECT p.id_persona FROM persona p
     JOIN usuario u ON p.id_persona = u.persona_id_persona
     WHERE p.dni = ? AND u.id_usuario != ?"
);
$stmtDni->bind_param("si", $_POST["dni"], $id_usuario_editar);
$stmtDni->execute();
$stmtDni->store_result();
if ($stmtDni->num_rows > 0) {
    $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'El DNI ya está registrado en otro usuario.'];
    header("Location: ../views/menu.php");
    exit;
}
$stmtDni->close();

// Verificar email duplicado (excluyendo al usuario actual)
$stmtEmail = $conexion->prepare("SELECT id_usuario FROM usuario WHERE email = ? AND id_usuario != ?");
$stmtEmail->bind_param("si", $_POST["email"], $id_usuario_editar);
$stmtEmail->execute();
$stmtEmail->store_result();
if ($stmtEmail->num_rows > 0) {
    $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'El email ya está registrado en otro usuario.'];
    header("Location: ../views/menu.php");
    exit;
}
$stmtEmail->close();

// Obtener id_persona
$stmtP = $conexion->prepare("SELECT persona_id_persona FROM usuario WHERE id_usuario = ?");
$stmtP->bind_param("i", $id_usuario_editar);
$stmtP->execute();
$resP = $stmtP->get_result()->fetch_assoc();
$stmtP->close();

if (!$resP) {
    $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'Usuario no encontrado.'];
    header("Location: ../views/menu.php");
    exit;
}
$id_persona = $resP['persona_id_persona'];

// Actualizar persona
$stmtUP = $conexion->prepare(
    "UPDATE persona SET nombre = ?, apellido = ?, fecha_nacimiento = ?, dni = ? WHERE id_persona = ?"
);
$stmtUP->bind_param("ssssi",
    $_POST["nombre"],
    $_POST["apellido"],
    $_POST["fecha_nacimiento"],
    $_POST["dni"],
    $id_persona
);
$okPersona = $stmtUP->execute();
$stmtUP->close();

// Actualizar usuario (con o sin contraseña)
if (!empty($_POST["contrasenia"])) {
    $hash = password_hash($_POST["contrasenia"], PASSWORD_BCRYPT, ["cost" => 12]);
    $stmtUU = $conexion->prepare(
        "UPDATE usuario SET email = ?, id_perfil = ?, contrasenia = ? WHERE id_usuario = ?"
    );
    $stmtUU->bind_param("sisi", $_POST["email"], $id_perfil, $hash, $id_usuario_editar);
} else {
    $stmtUU = $conexion->prepare(
        "UPDATE usuario SET email = ?, id_perfil = ? WHERE id_usuario = ?"
    );
    $stmtUU->bind_param("sii", $_POST["email"], $id_perfil, $id_usuario_editar);
}
$okUsuario = $stmtUU->execute();
$stmtUU->close();

$objeto->desconectar($conexion);

if ($okPersona && $okUsuario) {
    $_SESSION['alerta'] = ['tipo' => 'success', 'titulo' => '¡Éxito!', 'msg' => 'Usuario modificado correctamente.'];
} else {
    $_SESSION['alerta'] = ['tipo' => 'error', 'titulo' => 'Error', 'msg' => 'No se pudo modificar el usuario.'];
}
header("Location: ../views/menu.php");
exit;