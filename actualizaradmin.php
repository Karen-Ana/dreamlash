<?php 
session_start();

$n = $_POST['nombre'];
$pa = $_POST['precio'];
$sa = $_POST['cantidad'];



include"./../includes/config.php";

$peticion = "UPDATE usuarios SET nombre ='$n',precio = '$pa', cantidad = '$sa', WHERE id = ".$_SESSION['idu']."";
$respuesta = mysqli_query($conexion, $peticion);

header("location:actualizar.php");

?>