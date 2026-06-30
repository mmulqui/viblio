<?php
/**
 * Perfil — entidad que representa un perfil/rol del sistema.
 */
class Perfil
{
    public function __construct(
        public readonly int    $idPerfil,
        public readonly string $tipoPerfil,
        public readonly bool   $activo,
    ) {}

    public static function desdeArray(array $data): self
    {
        return new self(
            idPerfil:   (int)  $data['id_perfil'],
            tipoPerfil: $data['tipo_perfil'],
            activo:     (bool) $data['activo'],
        );
    }

    public function toArray(): array
    {
        return [
            'id_perfil'   => $this->idPerfil,
            'tipo_perfil' => $this->tipoPerfil,
            'activo'      => (int) $this->activo,
        ];
    }
}
