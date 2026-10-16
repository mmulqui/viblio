<?php

require_once __DIR__ . '/Database.php';

class Auditoria {

    public static function registrar(mysqli $db, ?int $idUsuario, string $modulo, string $accion, string $detalle = ''): void {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $fecha = date('Y-m-d H:i:s');

        $stmt = $db->prepare(
            "INSERT INTO auditoria (id_usuario, modulo, accion, detalle, ip, fecha) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('isssss', $idUsuario, $modulo, $accion, $detalle, $ip, $fecha);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Atajo para auditar sin pasar conexión ni usuario: toma la conexión de Database
     * y el usuario de la sesión. Si falla, deja el error en el log de PHP y NO
     * interrumpe la operación que se estaba auditando.
     */
    public static function log(string $modulo, string $accion, string $detalle = '', ?int $idUsuario = null): void {
        try {
            if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
                $idUsuario = (int) $_SESSION['id_usuario'];
            }
            self::registrar(Database::getConexion(), $idUsuario, $modulo, $accion, $detalle);
        } catch (Throwable $e) {
            error_log('Auditoria::log: ' . $e->getMessage());
        }
    }

    /** "Nombre Apellido (rol, DNI x)" para usar en el detalle; si falla, cae a "usuario id: N". */
    public static function describirUsuario(int $idUsuario): string {
        try {
            $stmt = Database::getConexion()->prepare(
                "SELECT p.nombre, p.apellido, p.dni, pf.tipo_perfil
                 FROM usuario u
                 JOIN persona p ON p.id_persona = u.persona_id_persona
                 LEFT JOIN perfil pf ON pf.id_perfil = u.id_perfil
                 WHERE u.id_usuario = ? LIMIT 1"
            );
            $stmt->bind_param('i', $idUsuario);
            $stmt->execute();
            $f = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($f) {
                return $f['nombre'] . ' ' . $f['apellido'] . ' (' . ($f['tipo_perfil'] ?? 'sin perfil') . ', DNI ' . $f['dni'] . ')';
            }
        } catch (Throwable $e) {
            error_log('Auditoria::describirUsuario: ' . $e->getMessage());
        }
        return "usuario id: $idUsuario";
    }

    /** "Título (ISBN x)" para usar en el detalle; si falla, cae a "libro id: N". */
    public static function describirLibro(int $idLibro): string {
        try {
            $stmt = Database::getConexion()->prepare("SELECT titulo, isbn FROM libro WHERE id_libro = ? LIMIT 1");
            $stmt->bind_param('i', $idLibro);
            $stmt->execute();
            $f = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($f) {
                return $f['titulo'] . ' (ISBN ' . $f['isbn'] . ')';
            }
        } catch (Throwable $e) {
            error_log('Auditoria::describirLibro: ' . $e->getMessage());
        }
        return "libro id: $idLibro";
    }
}