<?php 
session_start();

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$ip = $_SERVER['REMOTE_ADDR'];
include "./../pages/config.php";

$peticion = "SELECT * FROM lash WHERE correo = '$correo' AND contrasena= '$contrasena'";
$respuesta = mysqli_query($conexion,$peticion);
$contador = 0;
while($fila = mysqli_fetch_array($respuesta)){
$contador++;
    $tipo = $fila['tipo'];
    $_SESSION['tipo']= $fila['tipo'];
    $_SESSION['idu']= $fila['id'];
}
if ($contador > 0 AND $tipo == 0){
    header("location:principal.php");
}else{
    header("location:index.php");
}
?>