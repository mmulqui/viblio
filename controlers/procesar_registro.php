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

$fechaNacimiento = $_POST["fecha_nacimiento"];
$hoy = new DateTime();
$fecha = Datetime::createfromformat("d-m-Y", $fechaNacimiento);

if (!$fecha || $fecha > $hoy){
    echo "<script>
            alert('La fecha de nacimiento  no puede ser una fecha futura.');
          </script>"
    exit;
}

$fechaParaDB = $fecha->format('Y-m-d');

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
    exit;
}

$passwordSecurity = password_hash($_POST["contrasenia"], PASSWORD_BCRYPT, ["cost" => 12]);


$stmt = $conexion->prepare("call registrar_alumno(?,?,?,?,?,?)");
$stmt->bind_param(
    "ssssss",
    $_POST["nombre"],
    $_POST["apellido"],
    $fechaParaDB,
    $_POST["dni"],
    $_POST["email"],
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

