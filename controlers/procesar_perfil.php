<?php
require_once("../controlers/auth.php");
verificarRol(["bibliotecario"]);
require_once("../models/conexion.php");

$objeto   = new conexion();
$conexion = $objeto->conectar();

$accion = $_POST["accion"] ?? "";

// ===================== ELIMINAR (baja lógica, AJAX -> JSON) =====================
if ($accion === "eliminar") {
    header('Content-Type: application/json');

    $id_perfil = (int) ($_POST["id_perfil"] ?? 0);

    if ($id_perfil <= 0) {
        echo json_encode(['ok' => false, 'msg' => 'Perfil inválido.']);
        exit;
    }

    // Evitar que se desactiven los perfiles base del sistema
    if (in_array($id_perfil, [1, 2, 3])) {
        echo json_encode(['ok' => false, 'msg' => 'No se puede eliminar un perfil base del sistema.']);
        exit;
    }

    $stmt = $conexion->prepare("UPDATE perfil SET activo = 0 WHERE id_perfil = ?");
    $stmt->bind_param("i", $id_perfil);
    $ok = $stmt->execute();
    $stmt->close();
    $objeto->desconectar($conexion);

    if ($ok) {
        echo json_encode(['ok' => true, 'msg' => 'Perfil eliminado (baja lógica) correctamente.']);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'No se pudo eliminar el perfil.']);
    }
    exit;
}

// ===================== AGREGAR (form -> redirect con SweetAlert) =====================
if ($accion === "agregar") {
    $tipo_perfil = trim($_POST["tipo_perfil"] ?? "");

    if ($tipo_perfil === "") {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'El nombre del perfil no puede estar vacío.'];
        $objeto->desconectar($conexion);
        header("Location: ../views/menu.php");
        exit;
    }

    // Verificar duplicado
    $stmt = $conexion->prepare("SELECT id_perfil FROM perfil WHERE tipo_perfil = ?");
    $stmt->bind_param("s", $tipo_perfil);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'Ya existe un perfil con ese nombre.'];
        $objeto->desconectar($conexion);
        header("Location: ../views/menu.php");
        exit;
    }
    $stmt->close();

    $stmt = $conexion->prepare("INSERT INTO perfil (tipo_perfil, activo) VALUES (?, 1)");
    $stmt->bind_param("s", $tipo_perfil);
    $ok = $stmt->execute();
    $stmt->close();
    $objeto->desconectar($conexion);

    if ($ok) {
        $_SESSION['alerta'] = ['tipo' => 'success', 'titulo' => '¡Éxito!', 'msg' => 'Perfil agregado correctamente.'];
    } else {
        $_SESSION['alerta'] = ['tipo' => 'error', 'titulo' => 'Error', 'msg' => 'No se pudo agregar el perfil.'];
    }
    header("Location: ../views/menu.php");
    exit;
}

// ===================== MODIFICAR (form -> redirect con SweetAlert) =====================
if ($accion === "modificar") {
    $id_perfil   = (int) ($_POST["id_perfil"] ?? 0);
    $tipo_perfil = trim($_POST["tipo_perfil"] ?? "");

    if ($id_perfil <= 0 || $tipo_perfil === "") {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'Datos inválidos.'];
        $objeto->desconectar($conexion);
        header("Location: ../views/menu.php");
        exit;
    }

    // Verificar duplicado (excluyendo el propio perfil)
    $stmt = $conexion->prepare("SELECT id_perfil FROM perfil WHERE tipo_perfil = ? AND id_perfil != ?");
    $stmt->bind_param("si", $tipo_perfil, $id_perfil);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'Ya existe un perfil con ese nombre.'];
        $objeto->desconectar($conexion);
        header("Location: ../views/menu.php");
        exit;
    }
    $stmt->close();

    $stmt = $conexion->prepare("UPDATE perfil SET tipo_perfil = ? WHERE id_perfil = ?");
    $stmt->bind_param("si", $tipo_perfil, $id_perfil);
    $ok = $stmt->execute();
    $stmt->close();
    $objeto->desconectar($conexion);

    if ($ok) {
        $_SESSION['alerta'] = ['tipo' => 'success', 'titulo' => '¡Éxito!', 'msg' => 'Perfil modificado correctamente.'];
    } else {
        $_SESSION['alerta'] = ['tipo' => 'error', 'titulo' => 'Error', 'msg' => 'No se pudo modificar el perfil.'];
    }
    header("Location: ../views/menu.php");
    exit;
}

// ===================== ACCIÓN DESCONOCIDA =====================
$objeto->desconectar($conexion);
$_SESSION['alerta'] = ['tipo' => 'warning', 'titulo' => 'Ups', 'msg' => 'Acción no reconocida.'];
header("Location: ../views/menu.php");
exit;