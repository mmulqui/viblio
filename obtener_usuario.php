<?php
require_once("conexion.php");

header('Content-Type: application/json');

if(isset($_GET['dni'])) { 
    $objeto = new conexion();
    $conexion = $objeto->conectar();
    
    $dni = mysqli_real_escape_string($conexion, $_GET['dni']);
    
    $sql = "SELECT 
                usuario.id_usuario,
                usuario.email,
                persona.dni,
                persona.nombre,
                persona.apellido,
                persona.fecha_nacimiento,
                COALESCE(alumno.numero_prestamos, 0) AS numero_prestamos,
                COALESCE(alumno.numero_multas, 0) AS numero_multas
            FROM usuario
            JOIN persona ON usuario.persona_id_persona = persona.id_persona
            LEFT JOIN alumno ON alumno.usuario_id_usuario = usuario.id_usuario
            WHERE persona.dni = '$dni'
            LIMIT 1";
    
    $result = $conexion->query($sql);
    
    if($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'usuario' => $usuario
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no encontrado con DNI: ' . $dni
        ]);
    }
    
    $objeto->desconectar($conexion);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'DNI no proporcionado'
    ]);
}
?>