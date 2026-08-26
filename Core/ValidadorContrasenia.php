<?php


/*
ESTE CÓDIGO SE RELACIONA CON:
Auth/ 
    LoginController.php
    RegistroController.php


*/


class ValidadorContrasenia {
    public static function password($password) {
        $errores = [];

        if (strlen($password) < 8) {
            $errores[] = "Debe tener mínimo 8 caracteres.";
        }




        if (!preg_match('/[A-Z]/', $password)) {
            $errores[] = "Debe tener mínimo una letra en mayuscula.";
        }



        if (!preg_match('/[!@#$%^&*(),.?":{}|<>_\-]/', $password)) {
            $errores[] = "Debe tener mínimo un caracter especial.";
        }

        

        return $errores;

    }




    
     public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? [] : ["Email inválido."];
    }

    // Usado en LoginController para validar que el campo no esté vacío
    public static function requerido($valor, $nombreCampo) {
        return trim($valor) !== '' ? [] : ["El campo $nombreCampo es obligatorio."];
    }



}




?>