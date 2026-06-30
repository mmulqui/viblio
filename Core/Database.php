<?php
/**
 * Database — conexión única a MySQL.
 */
class Database
{
    private static ?mysqli $conexion = null;

    // Impedir instanciación y clonación
    private function __construct() {}
    private function __clone() {}

    public static function getConexion(): mysqli
    {
        if (self::$conexion === null) {
            $raiz = dirname(__DIR__);
            if (file_exists("$raiz/config.php")) {
                require_once "$raiz/config.php";
            }

            self::$conexion = new mysqli(
                DB_HOST ?? 'localhost',
                DB_USER ?? 'root',
                DB_PASS ?? '',
                DB_NAME ?? 'viblio_db'
            );

            if (self::$conexion->connect_error) {
                throw new RuntimeException(
                    'Error de conexión a la BD: ' . self::$conexion->connect_error
                );
            }

            self::$conexion->set_charset('utf8mb4');
        }

        return self::$conexion;
    }

    public static function cerrar(): void
    {
        if (self::$conexion !== null) {
            self::$conexion->close();
            self::$conexion = null;
        }
    }
}
