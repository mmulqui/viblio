<?php
require_once('../models/conexion.php');
header('Content-Type: application/json');
if(isset($_GET['dni'])){
$objeto=new conexion();
$conexion=$objeto->conectar();
$dni=mysqli_real_escape_string($conexion,$_GET['dni']);
$sql="SELECT usuario.id_usuario,usuario.email,persona.dni,persona.nombre,persona.apellido,persona.fecha_nacimiento,pf.tipo_perfil AS rol,COALESCE(alumno.numero_prestamos,0) AS numero_prestamos,COALESCE(alumno.numero_multas,0) AS numero_multas FROM usuario JOIN persona ON usuario.persona_id_persona=persona.id_persona JOIN perfil pf ON usuario.id_perfil=pf.id_perfil LEFT JOIN alumno ON alumno.usuario_id_usuario=usuario.id_usuario WHERE persona.dni='$dni' LIMIT 1";
$result=$conexion->query($sql);
if($result&&$result->num_rows>0){$u=$result->fetch_assoc();echo json_encode(['success'=>true,'usuario'=>$u]);}else{echo json_encode(['success'=>false,'message'=>'No encontrado']);}}
else{echo json_encode(['success'=>false,'message'=>'DNI no proporcionado']);}
?>