<?php
require_once("conexion.php");

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

$passwordSecurity = password_hash($_POST["contrasenia"], PASSWORD_BCRYPT, ["cost" => 12]);
//se agrego el hash a la contraseña
$objeto = new conexion;
$conexion = $objeto->conectar();

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
            window.location.href='menu.php';
            </scrit>";
} else {
    error_log("Error al registrar alumno: " . $stmt->error);
    echo "<script>
            alert('Error al agregar usuario. Intente nuevamente.');
        </script>";
}

