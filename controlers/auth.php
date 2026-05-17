<?php

function verificarSesion(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION["id_usuario"])) {
        header("Location: /login_viblio/view_bibliotecario/views/index.php");
        exit();
    }

    if (time() - ($_SESSION["last_activity"] ?? 0) > 1800) {
        session_destroy();
        header("Location: /login_viblio/view_bibliotecario/views/index.php?error=" . urlencode("Sesión expirada por inactividad"));
        exit();
    }

    // Validar que no cambió la ip ni el navegador
    if ($_SESSION["ip"] !== $_SERVER["REMOTE_ADDR"] ||
        $_SESSION["ua"] !== $_SERVER["HTTP_USER_AGENT"]) {
        session_destroy();
        header("Location: /login_viblio/view_bibliotecario/views/index.php?error=" . urlencode("Sesión inválida"));
        exit();
    }

    $_SESSION["last_activity"] = time();
}

?>