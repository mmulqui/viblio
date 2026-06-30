<?php
require_once dirname(__DIR__) . '/Core/Database.php';

/**
 * UsuarioRepository — todas las consultas a la BD relacionadas con usuarios.
 */
class UsuarioRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // ─── Consultas de lectura ─────────────────────────────────────────────────

    /**
     * Lista usuarios activos, con búsqueda opcional.
     * Usa prepared statement para evitar inyección SQL.
     *
     * @return array[] filas asociativas
     */
    public function listar(string $busqueda = '', bool $todos = false): array
    {
        $base = "SELECT u.id_usuario, u.activo,
                        p.nombre, p.apellido, p.fecha_nacimiento, p.dni,
                        u.email,
                        pf.tipo_perfil AS rol,
                        a.numero_prestamos, a.numero_multas
                 FROM persona p
                 JOIN usuario u  ON p.id_persona = u.persona_id_persona
                 JOIN perfil pf  ON u.id_perfil  = pf.id_perfil
                 LEFT JOIN alumno a ON u.id_usuario = a.usuario_id_usuario
                 WHERE u.activo = 1";

        if ($todos || $busqueda === '') {
            return $this->db->query($base)->fetch_all(MYSQLI_ASSOC);
        }

        $stmt = $this->db->prepare(
            "$base AND (p.nombre    LIKE ?
                    OR  p.apellido  LIKE ?
                    OR  p.dni       LIKE ?
                    OR  u.email     LIKE ?)"
        );
        $param = "%$busqueda%";
        $stmt->bind_param('ssss', $param, $param, $param, $param);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }

    /** Obtiene los datos de un usuario por su DNI. */
    public function obtenerPorDni(string $dni): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.id_usuario, u.email, u.activo,
                    p.dni, p.nombre, p.apellido, p.fecha_nacimiento,
                    pf.tipo_perfil AS rol,
                    COALESCE(a.numero_prestamos, 0) AS numero_prestamos,
                    COALESCE(a.numero_multas, 0)    AS numero_multas
             FROM usuario u
             JOIN persona p  ON u.persona_id_persona = p.id_persona
             JOIN perfil pf  ON u.id_perfil           = pf.id_perfil
             LEFT JOIN alumno a ON a.usuario_id_usuario = u.id_usuario
             WHERE p.dni = ?
             LIMIT 1"
        );
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila ?: null;
    }

    /** Devuelve el id_persona de un usuario por su id_usuario. */
    public function obtenerIdPersona(int $idUsuario): ?int
    {
        $stmt = $this->db->prepare(
            "SELECT persona_id_persona FROM usuario WHERE id_usuario = ?"
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila ? (int) $fila['persona_id_persona'] : null;
    }

    /** Verifica si el email ya existe (excluyendo al usuario que se está editando). */
    public function emailExisteExcluyendo(string $email, int $idUsuario): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id_usuario FROM usuario WHERE email = ? AND id_usuario != ?"
        );
        $stmt->bind_param('si', $email, $idUsuario);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    /** Verifica si el DNI ya existe en otro usuario distinto. */
    public function dniExisteExcluyendo(string $dni, int $idUsuario): bool
    {
        $stmt = $this->db->prepare(
            "SELECT p.id_persona
             FROM persona p
             JOIN usuario u ON p.id_persona = u.persona_id_persona
             WHERE p.dni = ? AND u.id_usuario != ?"
        );
        $stmt->bind_param('si', $dni, $idUsuario);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    /** Llama al SP para registrar un alumno. */
    public function registrarAlumno(array $datos, string $passwordHash): bool
    {
        $stmt = $this->db->prepare("CALL registrar_alumno(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss',
            $datos['nombre'],
            $datos['apellido'],
            $datos['fecha_nacimiento'],
            $datos['dni'],
            $datos['email'],
            $passwordHash
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Modifica persona y usuario en una operación.
     *
     * @param string|null $hashContrasenia null = no cambiar contraseña
     */
    public function modificar(
        int     $idUsuario,
        int     $idPersona,
        int     $idPerfil,
        array   $datos,
        ?string $hashContrasenia
    ): bool {
        // Actualizar tabla persona
        $stmtP = $this->db->prepare(
            "UPDATE persona
             SET nombre = ?, apellido = ?, fecha_nacimiento = ?, dni = ?
             WHERE id_persona = ?"
        );
        $stmtP->bind_param('ssssi',
            $datos['nombre'], $datos['apellido'],
            $datos['fecha_nacimiento'], $datos['dni'],
            $idPersona
        );
        $okP = $stmtP->execute();
        $stmtP->close();

        // Actualizar tabla usuario (con o sin contraseña)
        if ($hashContrasenia) {
            $stmtU = $this->db->prepare(
                "UPDATE usuario SET email = ?, id_perfil = ?, contraseña = ? WHERE id_usuario = ?"
            );
            $stmtU->bind_param('sisi', $datos['email'], $idPerfil, $hashContrasenia, $idUsuario);
        } else {
            $stmtU = $this->db->prepare(
                "UPDATE usuario SET email = ?, id_perfil = ? WHERE id_usuario = ?"
            );
            $stmtU->bind_param('sii', $datos['email'], $idPerfil, $idUsuario);
        }
        $okU = $stmtU->execute();
        $stmtU->close();

        return $okP && $okU;
    }

    /** Baja lógica: pone activo = 0 al usuario con ese DNI. */
    public function darBajaLogica(string $dni): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE usuario u
             JOIN persona p ON u.persona_id_persona = p.id_persona
             SET u.activo = 0
             WHERE p.dni = ?"
        );
        $stmt->bind_param('s', $dni);
        $ok = $stmt->execute() && $stmt->affected_rows > 0;
        $stmt->close();
        return $ok;
    }
}
