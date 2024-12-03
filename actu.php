<?php 
session_start();

$n = $_POST['nombre'];
$cor = $_POST['correo'];
$con = $_POST['contrasena'];


include "./../pages/config.php";

$peticion = "UPDATE lash SET nombre ='$n', correo = '$cor', contrasena = '$con' WHERE id = ".$_SESSION['id']."";
$respuesta = mysqli_query($conexion, $peticion);

header("location:olvidar.php");

?>