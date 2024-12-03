<?php
$correo=$_POST ['correo'];
$contrasena=$_POST ['contrasena'];

include "./../pages/config.php";

$peticion ="INSERT INTO lash VALUES( NULL, '', '$correo', '$contrasena');";

$respuesta = mysqli_query($conexion, $peticion);

header("location:olvidar.php?mensaje=exitoalguardar");



?>