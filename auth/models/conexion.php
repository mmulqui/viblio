<?php
class conexion{
    private $servidor = "localhost";
    private $usuario = "root";
    private $contrasenia = "44344934";
    private $BaseDeDatos = "viblio_db";

    public function conectar(){
        $conexion = new mysqli($this->servidor,$this->usuario,$this->contrasenia,$this->BaseDeDatos);
        return $conexion;
    }
    public function desconectar($conexion){
        $conexion->close();
    }

    public function consultar($sql,$conexion){
        $resultado = $conexion->execute_query($sql);
        //$this->desconectar($conexion);
        return $resultado;
    }
}