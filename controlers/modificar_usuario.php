<?php
session_start();
require_once("conexion.php");
require_once("user_sesion.php");

$id_usuario_logueado = (int) $_SESSION["id_usuario"];
$id_usuario_editar = (int) $_POST["id_usuario"];

if (mismoUsuario($id_usuario_logueado, $id_usuario_editar)) {
    header("Location: menu.php?error=No podes editar tu propio usuario");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $objeto = new conexion();
    $conexion = $objeto->conectar();
    
    $id_usuario = mysqli_real_escape_string($conexion, $_POST['id_usuario']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
    
    $sql_persona = "SELECT persona_id_persona FROM usuario WHERE id_usuario = '$id_usuario'";
    $result = $conexion->query($sql_persona);
    
    if($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_persona = $row['persona_id_persona'];
        
        $sql_update_persona = "UPDATE persona SET 
                                nombre = '$nombre',
                                apellido = '$apellido',
                                fecha_nacimiento = '$fecha_nacimiento',
                                dni = '$dni'
                                WHERE id_persona = '$id_persona'";
        
        $sql_update_usuario = "UPDATE usuario SET 
                                email = '$email'";
        
        if(!empty($_POST['contrasenia'])) {
            $contrasenia = mysqli_real_escape_string($conexion, $_POST['contrasenia']);
            $sql_update_usuario .= ", contraseña = '$contrasenia'";
        }
        
        $sql_update_usuario .= " WHERE id_usuario = '$id_usuario'";
        
        if($conexion->query($sql_update_persona) && $conexion->query($sql_update_usuario)) {
            $objeto->desconectar($conexion);
            echo "<script>
                    alert('Usuario modificado exitosamente');
                    window.location.href='menu.php?tab=usuario';
                  </script>";
        } else {
            $error = $conexion->error;
            $objeto->desconectar($conexion);
            echo "<script>
                    alert('Error al modificar usuario: " . addslashes($error) . "');
                    window.history.back();
                  </script>";
        }
    } else {
        $objeto->desconectar($conexion);
        echo "<script>
                alert('Usuario no encontrado');
                window.history.back();
              </script>";
    }
    
} else {
    header("Location: menu.php");
    exit;
}
?>