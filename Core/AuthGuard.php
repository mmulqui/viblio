<?php
/**
 * AuthGuard — gestión de sesión, roles y protección de endpoints.
 *
 * Reemplaza las funciones sueltas de auth.php con una clase estática.
 */
class AuthGuard
{
    private const TIMEOUT_SEGUNDOS = 1800; // 30 minutos

    /** Verifica que exista una sesión válida, no expirada y no secuestrada. */
    public static function verificarSesion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['id_usuario'])) {
            self::redirigir('../views/index.php');
        }

        // Timeout por inactividad
        if (time() - ($_SESSION['last_activity'] ?? 0) > self::TIMEOUT_SEGUNDOS) {
            session_destroy();
            self::redirigir('../views/index.php?error=' . urlencode('Sesión expirada por inactividad'));
        }

        // Validar que no cambió la IP ni el agente de usuario
        if (
            ($_SESSION['ip'] ?? '') !== $_SERVER['REMOTE_ADDR'] ||
            ($_SESSION['ua'] ?? '') !== $_SERVER['HTTP_USER_AGENT']
        ) {
            session_destroy();
            self::redirigir('../views/index.php?error=' . urlencode('Sesión inválida'));
        }

        $_SESSION['last_activity'] = time();
    }

    /** Verifica sesión válida y que el rol sea uno de los permitidos. */
    public static function verificarRol(array $rolesPermitidos): void
    {
        self::verificarSesion();

        if (!in_array($_SESSION['rol'] ?? '', $rolesPermitidos, true)) {
            self::redirigir('../views/index.php?error=' . urlencode('Acceso denegado'));
        }
    }

    /**
     * Retorna true si el usuario logueado ES el mismo de la fila.
     * Usarlo para impedir que alguien se modifique/elimine a sí mismo.
     */
    public static function esElMismoUsuario(int $idLogueado, int $idFila): bool
    {
        return $idLogueado === $idFila;
    }

    private static function redirigir(string $url): never
    {
        header("Location: $url");
        exit();
    }
}
