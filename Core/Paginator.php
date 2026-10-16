<?php
/**
 * Paginator — paginación simple para las tablas de ViBlio.
 *
 * Cada tabla usa su propio parámetro GET (pag_usuarios, pag_libros, ...), así varias
 * secciones pueden estar paginadas a la vez dentro de la misma página (menu.php).
 * Los enlaces conservan el resto de la query string (búsquedas, filtros) y agregan
 * ?tab=... para que la página vuelva a abrir la pestaña correcta.
 *
 * Uso con una lista en memoria:
 *     $pag   = Paginator::desdeGet('pag_libros', 'libros', count($libros));
 *     $filas = $pag->slice($libros);
 *     echo $pag->render();
 *
 * Uso con SQL (LIMIT/OFFSET):
 *     $pag   = Paginator::desdeGet('pag_auditoria', 'auditoria', $total, 50);
 *     $filas = $repo->listar($pag->limite(), $pag->offset());
 */
class Paginator
{
    public const POR_PAGINA = 10;

    private int $total;
    private int $porPagina;
    private int $totalPaginas;
    private int $pagina;
    private string $param;
    private string $tab;

    public function __construct(string $param, string $tab, int $total, int $porPagina = self::POR_PAGINA, int $pagina = 1)
    {
        $this->param        = $param;
        $this->tab          = $tab;
        $this->total        = max(0, $total);
        $this->porPagina    = max(1, $porPagina);
        $this->totalPaginas = max(1, (int) ceil($this->total / $this->porPagina));
        $this->pagina       = min(max(1, $pagina), $this->totalPaginas);
    }

    /** Crea el paginador leyendo la página actual de $_GET[$param]. */
    public static function desdeGet(string $param, string $tab, int $total, int $porPagina = self::POR_PAGINA): self
    {
        $valor  = $_GET[$param] ?? 1;
        $pagina = is_scalar($valor) ? (int) $valor : 1;
        return new self($param, $tab, $total, $porPagina, $pagina);
    }

    public function pagina(): int       { return $this->pagina; }
    public function totalPaginas(): int { return $this->totalPaginas; }
    public function limite(): int       { return $this->porPagina; }
    public function offset(): int       { return ($this->pagina - 1) * $this->porPagina; }

    /** Devuelve solo las filas de la página actual de una lista en memoria. */
    public function slice(array $filas): array
    {
        return array_slice($filas, $this->offset(), $this->porPagina);
    }

    /** HTML de la barra de paginación ('' si no hay filas). */
    public function render(): string
    {
        if ($this->total === 0) {
            return '';
        }

        $desde = $this->offset() + 1;
        $hasta = min($this->total, $this->offset() + $this->porPagina);

        $html  = '<nav class="paginacion" aria-label="Paginación">';
        $html .= "<span class=\"paginacion-info\">Mostrando {$desde}–{$hasta} de {$this->total}</span>";

        if ($this->totalPaginas > 1) {
            $html .= '<ul>';
            $html .= $this->item('«', $this->pagina - 1, $this->pagina > 1, false, 'Página anterior');
            foreach ($this->ventana() as $n) {
                $html .= $n === null
                    ? '<li class="puntos">…</li>'
                    : $this->item((string) $n, $n, true, $n === $this->pagina, "Página $n");
            }
            $html .= $this->item('»', $this->pagina + 1, $this->pagina < $this->totalPaginas, false, 'Página siguiente');
            $html .= '</ul>';
        }

        return $html . '</nav>';
    }

    /** Números a mostrar: primera, última y 2 a cada lado de la actual; null = "…". */
    private function ventana(): array
    {
        $paginas = array_unique(array_filter(
            array_merge([1, $this->totalPaginas], range($this->pagina - 2, $this->pagina + 2)),
            fn(int $n) => $n >= 1 && $n <= $this->totalPaginas
        ));
        sort($paginas);

        $salida = [];
        $previa = 0;
        foreach ($paginas as $n) {
            if ($n - $previa > 1) {
                $salida[] = null;
            }
            $salida[] = $n;
            $previa   = $n;
        }
        return $salida;
    }

    private function item(string $texto, int $pagina, bool $habilitado, bool $actual, string $etiqueta = ''): string
    {
        $etiqueta = htmlspecialchars($etiqueta);
        if ($actual) {
            return "<li class=\"activa\"><span aria-current=\"page\">$texto</span></li>";
        }
        if (!$habilitado) {
            return "<li class=\"deshabilitado\"><span>$texto</span></li>";
        }
        return "<li><a href=\"" . $this->url($pagina) . "\" aria-label=\"$etiqueta\">$texto</a></li>";
    }

    /** URL de una página: conserva la query string actual y fija la página y la pestaña. */
    private function url(int $pagina): string
    {
        $query = array_merge($_GET, [$this->param => $pagina, 'tab' => $this->tab]);
        return htmlspecialchars('?' . http_build_query($query));
    }
}