<?php

class Prestamo
{
    public function __construct(
        public readonly int $idPrestamo,
        public readonly int $codigoPrestamo,
        public readonly string $fechaPrestamo,
        public readonly string $fechaVencimiento,
        public readonly ?string $fechaDevolucion,
        public readonly int $idLibro,
        public readonly int $idEstado,
        public readonly int $idUsuario,
        public readonly ?string $estadoDescripcion = null,
        public readonly ?string $tituloLibro = null,
    ) {}

    public static function desdeArray(array $data): self
    {
        return new self(
            idPrestamo: (int) $data['id_prestamo'],
            codigoPrestamo: (int) $data['codigo_prestamo'],
            fechaPrestamo: $data['fecha_prestamo'],
            fechaVencimiento: $data['fecha_vencimiento'],
            fechaDevolucion: $data['fecha_devolucion'] ?? null,
            idLibro: (int) $data['id_libro'],
            idEstado: (int) $data['id_estado'],
            idUsuario: (int) $data['id_usuario'],
            estadoDescripcion: $data['estado_descripcion'] ?? null,
            tituloLibro: $data['titulo'] ?? null,  
        );
    }

    public function toArray(): array
    {
        return [
            'id_prestamo' => $this->idPrestamo,
            'codigo_prestamo' => $this->codigoPrestamo,
            'fecha_prestamo' => $this->fechaPrestamo,
            'fecha_vencimiento' => $this->fechaVencimiento,
            'fecha_devolucion' => $this->fechaDevolucion,
            'id_libro' => $this->idLibro,
            'id_estado' => $this->idEstado,
            'id_usuario' => $this->idUsuario,
            'estado_descripcion' => $this->estadoDescripcion,
            'titulo_libro' => $this->tituloLibro,
        ];
    }
}