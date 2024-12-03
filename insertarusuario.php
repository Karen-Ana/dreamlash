<?php 
session_start();
$n = $_POST['nombre'];
$cr = $_POST['correo'];
$cn = $_POST['contrasena'];


include "./../pages/config.php";

$peticion ="INSERT INTO lash VALUES(NULL, '$n', '$cr', '$cn')";
$respuesta = mysqli_query($conexion, $peticion);

header("location:index.php");
?>