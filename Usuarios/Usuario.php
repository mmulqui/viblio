<?php
/**
 * Usuario — entidad que representa un usuario del sistema.
 *
 * Es un objeto de solo lectura (readonly) que agrupa los datos
 * de las tablas persona + usuario + perfil + alumno.
 */
class Usuario
{
    public function __construct(
        public readonly int     $idUsuario,
        public readonly string  $email,
        public readonly string  $nombre,
        public readonly string  $apellido,
        public readonly string  $fechaNacimiento,
        public readonly string  $dni,
        public readonly string  $rol,
        public readonly bool    $activo,
        public readonly ?int    $numeroPrestamos = null,
        public readonly ?int    $numeroMultas    = null,
    ) {}

    /**
     * Crea un Usuario a partir de la fila asociativa que devuelve MySQLi.
     */
    public static function desdeArray(array $data): self
    {
        return new self(
            idUsuario:       (int) $data['id_usuario'],
            email:           $data['email'],
            nombre:          $data['nombre'],
            apellido:        $data['apellido'],
            fechaNacimiento: $data['fecha_nacimiento'],
            dni:             $data['dni'],
            rol:             $data['rol'],
            activo:          (bool) ($data['activo'] ?? true),
            numeroPrestamos: isset($data['numero_prestamos']) ? (int) $data['numero_prestamos'] : null,
            numeroMultas:    isset($data['numero_multas'])    ? (int) $data['numero_multas']    : null,
        );
    }

    /** Devuelve el objeto como array asociativo (útil para JSON). */
    public function toArray(): array
    {
        return [
            'id_usuario'       => $this->idUsuario,
            'email'            => $this->email,
            'nombre'           => $this->nombre,
            'apellido'         => $this->apellido,
            'fecha_nacimiento' => $this->fechaNacimiento,
            'dni'              => $this->dni,
            'rol'              => $this->rol,
            'activo'           => (int) $this->activo,
            'numero_prestamos' => $this->numeroPrestamos,
            'numero_multas'    => $this->numeroMultas,
        ];
    }
}
