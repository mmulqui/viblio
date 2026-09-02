<?php
/**
 * Reserva — entidad que representa la reserva de un libro.
 */
class Reserva
{
    public function __construct(
        public readonly int     $idReserva,
        public readonly int     $codigoReserva,
        public readonly string  $fechaSolicitud,
        public readonly string  $fechaReserva,
        public readonly int     $idLibro,
        public readonly int     $idEstado,
        public readonly int     $idUsuario,
        public readonly ?string $estadoDescripcion = null,
        public readonly ?string $tituloLibro       = null,
    ) {}

    public static function desdeArray(array $data): self
    {
        return new self(
            idReserva:         (int) $data['id_reserva'],
            codigoReserva:     (int) $data['codigo_reserva'],
            fechaSolicitud:    $data['fecha_solicitud'],
            fechaReserva:      $data['fecha_reserva'],
            idLibro:           (int) $data['id_libro'],
            idEstado:          (int) $data['id_estado'],
            idUsuario:         (int) $data['id_usuario'],
            estadoDescripcion: $data['estado_descripcion'] ?? null,
            tituloLibro:       $data['titulo'] ?? null,
        );
    }

    /** Devuelve el objeto como array asociativo (útil para JSON). */
    public function toArray(): array
    {
        return [
            'id_reserva'         => $this->idReserva,
            'codigo_reserva'     => $this->codigoReserva,
            'fecha_solicitud'    => $this->fechaSolicitud,
            'fecha_reserva'      => $this->fechaReserva,
            'id_libro'           => $this->idLibro,
            'id_estado'          => $this->idEstado,
            'id_usuario'         => $this->idUsuario,
            'estado_descripcion' => $this->estadoDescripcion,
            'titulo_libro'       => $this->tituloLibro,
        ];
    }
}