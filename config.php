<?php

// Iniciar sesión de forma segura si aún no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar el carrito si no existe en la sesión
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}





// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '44344934');
define('DB_NAME', 'viblio_db');
