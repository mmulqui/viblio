<?php
/**
 * BoletaImagen — valida y procesa la imagen de la boleta de una compra de libros.
 *
 * Acepta JPG, PNG o WEBP, comprueba que sea realmente una imagen (no confía en la
 * extensión ni en el tipo que informa el navegador) y la guarda reducida y recomprimida
 * como JPEG. Reducirla sirve para dos cosas: que entre sin problemas en la base
 * (max_allowed_packet de XAMPP es chico) y que se descarte cualquier contenido que no
 * sea la imagen en sí.
 */
class BoletaImagen
{
    /** Tamaño máximo del archivo que se acepta subir. */
    public const MAX_SUBIDA_BYTES = 8 * 1024 * 1024;

    /** Tamaño máximo de lo que se guarda en la base. */
    public const MAX_GUARDADO_BYTES = 900 * 1024;

    /** Lado máximo (en píxeles) de la imagen guardada. */
    public const MAX_LADO_PX = 1600;

    /** Imágenes con más píxeles que esto se rechazan (evita agotar la memoria al decodificarlas). */
    private const MAX_PIXELES = 50000000;

    /** Solo para pruebas por consola, donde no existe una subida HTTP real. */
    public static bool $omitirVerificacionDeSubida = false;

    /**
     * @param array|null $archivo Un elemento de $_FILES (ej. $_FILES['boleta']).
     * @return array{ok:bool, vacio:bool, datos:?string, mime:?string, error:?string}
     *         vacio = true cuando no se eligió ningún archivo (no es un error).
     */
    public static function procesar(?array $archivo): array
    {
        if ($archivo === null || ($archivo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return self::resultado(true, true);
        }

        switch ($archivo['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return self::error('La imagen es demasiado grande (máximo ' . (self::MAX_SUBIDA_BYTES / 1048576) . ' MB).');
            default:
                return self::error('No se pudo subir la imagen. Probá de nuevo.');
        }

        $tmp = (string) ($archivo['tmp_name'] ?? '');
        if (!self::$omitirVerificacionDeSubida && !is_uploaded_file($tmp)) {
            return self::error('No se pudo subir la imagen. Probá de nuevo.');
        }
        if (!is_file($tmp) || filesize($tmp) === 0) {
            return self::error('El archivo está vacío.');
        }
        if (filesize($tmp) > self::MAX_SUBIDA_BYTES) {
            return self::error('La imagen es demasiado grande (máximo ' . (self::MAX_SUBIDA_BYTES / 1048576) . ' MB).');
        }

        // Se mira el contenido real del archivo, no el nombre ni el tipo que dice el navegador.
        $info = @getimagesize($tmp);
        if ($info === false || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            return self::error('El archivo tiene que ser una imagen JPG, PNG o WEBP.');
        }
        if ($info[0] * $info[1] > self::MAX_PIXELES) {
            return self::error('La imagen tiene demasiados píxeles. Subí una foto más chica.');
        }

        $contenido = file_get_contents($tmp);

        // Sin la extensión GD no se puede reducir: se acepta solo si ya es chica.
        if (!function_exists('imagecreatefromstring') || !function_exists('imagejpeg')) {
            if (strlen($contenido) > self::MAX_GUARDADO_BYTES) {
                return self::error('Subí una imagen de hasta ' . (self::MAX_GUARDADO_BYTES / 1024) . ' KB (el servidor no tiene la extensión GD para reducirla).');
            }
            return self::resultado(true, false, $contenido, $info['mime']);
        }

        // GD decodifica a medias una imagen cortada y rellena lo que falta, sin avisar:
        // se comprueba antes que el archivo esté completo para no guardar una boleta trunca.
        if (!self::estaCompleta($contenido, $info[2])) {
            return self::error('La imagen está incompleta o dañada. Probá subirla de nuevo.');
        }

        $imagen = @imagecreatefromstring($contenido);
        if ($imagen === false) {
            return self::error('La imagen está dañada o no se pudo leer.');
        }
        if ($info[2] === IMAGETYPE_JPEG) {
            $imagen = self::corregirOrientacion($imagen, $tmp);
        }

        $jpeg = self::comprimir($imagen);
        if ($jpeg === null) {
            return self::error('No se pudo reducir la imagen lo suficiente. Probá con una foto más chica.');
        }
        return self::resultado(true, false, $jpeg, 'image/jpeg');
    }

    /** ¿El archivo llegó entero? Mira el final real de la estructura de cada formato. */
    private static function estaCompleta(string $d, int $tipo): bool
    {
        return match ($tipo) {
            IMAGETYPE_JPEG => self::jpegCompleto($d),
            // PNG: el último bloque es IEND
            IMAGETYPE_PNG  => strpos($d, 'IEND') !== false,
            // WEBP: la cabecera RIFF declara cuántos bytes tiene el archivo
            IMAGETYPE_WEBP => strlen($d) >= 12 && (unpack('V', substr($d, 4, 4))[1] + 8) <= strlen($d),
            default        => true,
        };
    }

    /**
     * Recorre los segmentos de un JPEG hasta encontrar la marca de fin (FFD9).
     * Si se termina el archivo antes, estaba cortado. Los datos de imagen del JPEG nunca
     * contienen FFD9 (los FF se escriben como FF00), por eso la marca solo puede ser el final real.
     */
    private static function jpegCompleto(string $d): bool
    {
        $largo = strlen($d);
        if ($largo < 4 || $d[0] !== "\xFF" || $d[1] !== "\xD8") {
            return false;
        }

        $i = 2;
        while ($i + 1 < $largo) {
            if ($d[$i] !== "\xFF") {                    // basura entre segmentos: se busca el próximo marcador
                $i = strpos($d, "\xFF", $i);
                if ($i === false) {
                    return false;
                }
                continue;
            }
            $marca = ord($d[$i + 1]);
            if ($marca === 0xFF) {                      // relleno
                $i++;
                continue;
            }
            if ($marca === 0xD9) {                      // EOI: el archivo está completo
                return true;
            }
            if ($marca === 0x00 || $marca === 0x01 || $marca === 0xD8 || ($marca >= 0xD0 && $marca <= 0xD7)) {
                $i += 2;                                // marcadores sin contenido
                continue;
            }
            if ($i + 3 >= $largo) {
                return false;
            }
            $i += 2 + ((ord($d[$i + 2]) << 8) | ord($d[$i + 3])); // salta el segmento (incluye miniaturas EXIF)

            if ($marca === 0xDA) {                      // SOS: sigue la imagen comprimida hasta el próximo marcador real
                while (true) {
                    $i = strpos($d, "\xFF", $i);
                    if ($i === false || $i + 1 >= $largo) {
                        return false;
                    }
                    $siguiente = ord($d[$i + 1]);
                    if ($siguiente === 0x00 || ($siguiente >= 0xD0 && $siguiente <= 0xD7)) {
                        $i += 2;
                        continue;
                    }
                    if ($siguiente === 0xFF) {
                        $i++;
                        continue;
                    }
                    break;
                }
            }
        }
        return false;
    }

    /** Las fotos de celular traen la rotación en los datos EXIF: se aplica para que se vean derechas. */
    private static function corregirOrientacion(\GdImage $imagen, string $ruta): \GdImage
    {
        if (!function_exists('exif_read_data') || !function_exists('imagerotate')) {
            return $imagen;
        }
        $exif = @exif_read_data($ruta);
        $angulo = match ((int) ($exif['Orientation'] ?? 1)) {
            3       => 180,
            6       => -90,
            8       => 90,
            default => 0,
        };
        if ($angulo === 0) {
            return $imagen;
        }
        $rotada = imagerotate($imagen, $angulo, 0);
        return $rotada !== false ? $rotada : $imagen;
    }

    /** Reduce y recomprime hasta que entre en MAX_GUARDADO_BYTES. null si no se logra. */
    private static function comprimir(\GdImage $origen): ?string
    {
        $ancho = imagesx($origen);
        $alto  = imagesy($origen);
        $lado  = self::MAX_LADO_PX;

        for ($reduccion = 0; $reduccion < 5; $reduccion++) {
            $escala = min(1, $lado / max($ancho, $alto));
            $nuevoAncho = max(1, (int) round($ancho * $escala));
            $nuevoAlto  = max(1, (int) round($alto * $escala));

            $lienzo = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            imagefill($lienzo, 0, 0, imagecolorallocate($lienzo, 255, 255, 255)); // fondo blanco para PNG con transparencia
            imagecopyresampled($lienzo, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

            foreach ([82, 72, 62, 50] as $calidad) {
                ob_start();
                imagejpeg($lienzo, null, $calidad);
                $bytes = (string) ob_get_clean();
                if (strlen($bytes) <= self::MAX_GUARDADO_BYTES) {
                    return $bytes;
                }
            }
            $lado = (int) round($lado * 0.75); // todavía pesa mucho: se achica la imagen y se reintenta
        }
        return null;
    }

    private static function error(string $mensaje): array
    {
        return ['ok' => false, 'vacio' => false, 'datos' => null, 'mime' => null, 'error' => $mensaje];
    }

    private static function resultado(bool $ok, bool $vacio, ?string $datos = null, ?string $mime = null): array
    {
        return ['ok' => $ok, 'vacio' => $vacio, 'datos' => $datos, 'mime' => $mime, 'error' => null];
    }
}