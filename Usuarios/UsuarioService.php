<?php
/**
 * UsuarioService — lógica de negocio para usuarios.
 */
class UsuarioService
{
    /**
     * Valida que los campos requeridos estén presentes en $_POST.
     * Retorna el mensaje de error, o null si todo está bien.
     */
    public function validarCamposObligatorios(array $campos, array $post): ?string
    {
        foreach ($campos as $campo) {
            if (empty($post[$campo])) {
                return 'Todos los campos son obligatorios.';
            }
        }
        return null;
    }

    /**
     * Valida que la fecha de nacimiento sea anterior a hoy.
     */
    public function validarFechaNacimiento(string $fecha): bool
    {
        $hoy     = new DateTime();
        $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
        return $fechaObj !== false && $fechaObj < $hoy;
    }

    /**
     * Formatea la fecha para guardar en la BD (Y-m-d).
     */
    public function formatearFecha(string $fecha): string
    {
        return DateTime::createFromFormat('Y-m-d', $fecha)->format('Y-m-d');
    }

    /**
     * Hashea una contraseña con bcrypt (cost 12).
     */
    public function hashContrasenia(string $contrasenia): string
    {
        return password_hash($contrasenia, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Retorna true si las dos contraseñas son iguales.
     */
    public function contrasenasCoinciden(string $pass, string $confirmar): bool
    {
        return $pass === $confirmar;
    }

    /**
     * Compara los datos de un usuario ANTES de modificarlo contra lo que
     * llegó en $_POST, y arma un string legible con los campos que
     * efectivamente cambiaron, en formato "campo: valor_viejo -> valor_nuevo".
     *
     * Se usa para dejar registro detallado en la auditoría.
     *
     * @param array $datosAntes  fila devuelta por UsuarioRepository::obtenerPorId()
     * @param array $post        $_POST del formulario de modificación
     * @param bool  $cambioContrasenia  si se cambió la contraseña (no se comparan hashes)
     */
    public function compararCambios(array $datosAntes, array $post, bool $cambioContrasenia = false): string
    {
        // Mapea campo del formulario => campo de la fila de BD
        $campos = [
            'nombre'           => 'nombre',
            'apellido'         => 'apellido',
            'fecha_nacimiento' => 'fecha_nacimiento',
            'dni'              => 'dni',
            'email'            => 'email',
            'rol'              => 'rol',
        ];

        $cambios = [];
        foreach ($campos as $campoPost => $campoBD) {
            if (!isset($post[$campoPost])) {
                continue;
            }
            $valorAntes  = (string) ($datosAntes[$campoBD] ?? '');
            $valorNuevo  = trim((string) $post[$campoPost]);

            if ($valorAntes !== $valorNuevo) {
                $cambios[] = "$campoPost: \"$valorAntes\" -> \"$valorNuevo\"";
            }
        }

        if ($cambioContrasenia) {
            $cambios[] = 'contraseña: (modificada)';
        }

        return implode('; ', $cambios);
    }
}