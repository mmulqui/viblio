<?php
/**
 * Libro — entidad que representa un libro del catálogo.
 */
class Libro
{
    public function __construct(
        public readonly ?int   $idLibro,
        public readonly string $isbn,
        public readonly string $titulo,
        public readonly string $edicion,
        public readonly int    $anioPublicacion,
        public readonly int    $estado,
        public readonly string $autor,
        public readonly string $editorial,
        public readonly string $categoria,
        public readonly string $genero,
    ) {}

    public static function desdeArray(array $data): self
    {
        return new self(
            idLibro:         isset($data['id_libro']) ? (int) $data['id_libro'] : null,
            isbn:            $data['isbn'],
            titulo:          $data['titulo'],
            edicion:         $data['edicion'] ?? '',
            anioPublicacion: (int) $data['anio_publicacion'],
            estado:          (int) $data['estado'],
            autor:           $data['autor'],
            editorial:       $data['editorial'],
            categoria:       $data['categoria'],
            genero:          $data['genero'],
        );
    }

    public function toArray(): array
    {
        return [
            'id_libro'        => $this->idLibro,
            'isbn'            => $this->isbn,
            'titulo'          => $this->titulo,
            'edicion'         => $this->edicion,
            'anio_publicacion'=> $this->anioPublicacion,
            'estado'          => $this->estado,
            'autor'           => $this->autor,
            'editorial'       => $this->editorial,
            'categoria'       => $this->categoria,
            'genero'          => $this->genero,
        ];
    }
}
