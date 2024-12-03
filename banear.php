<?php
    include "./../pages/config.php";
    $id = $_GET['id'];
    $peticion = "DELETE FROM lash WHERE id = $id";
    $respuesta = mysqli_query($conexion, $peticion);
    
    header("location:olvidar.php");
    ?>