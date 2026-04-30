<?php
require_once("../models/conexion.php");

$campos = ["nombre","apellido","fecha_nacimiento","dni","email","contrasenia"];
foreach($campos as $campo){
    if (empty($_POST[$campo])){
        echo"<script>
                alert('todos los campos son obligatorios');
                window.history.back();
            </script>";
        exit;
    }
}

$objeto = new conexion;
$conexion = $objeto->conectar();

$email = $_POST["email"];
$checkEmail = $conexion->query("select id_usuario from usuario where email = '$email'");
if ($checkEmail->num_rows > 0){
    echo "<script>
            alert('Los datos ya han sido registrados. Use otros.');
            window.history.back();
        </script>";
    exit;
}

$dni = $_POST["dni"];
$checkDni = $conexion->query("select id_persona from persona where dni = '$dni'");
if ($checkDni->num_rows > 0){
    echo "<script>
            alert('Los datos ya han sido registrados. Use otros.');
            window.history.back();
        </script>";
}

$passwordSecurity = password_hash($_POST["contrasenia"], PASSWORD_BCRYPT, ["cost" => 12]);
//se agrego el hash a la contraseña

$stmt = $conexion->prepare("call registrar_alumno(?,?,?,?,?,?)");
$stmt->bind_param(
    "ssssss",
    $_POST["nombre"],
    $_POST["apellido"],
    $_POST["fecha_nacimiento"],
    $_POST["dni"],
    $_POST["email"], // verificar que este dato sea unico por cada usuario
    $passwordSecurity
);

if ($stmt->execute()) {
    echo "<script>
            alert('Usuario agregado exitosamente');
            window.location.href='../views/menu.php';
            </script>";
} else {
    error_log("Error al registrar alumno: " . $stmt->error);
    echo "<script>
            alert('Error al agregar usuario. Intente nuevamente.');
        </script>";
}

