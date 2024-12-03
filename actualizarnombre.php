<?php
     include "./../pages/config.php";
    $peticion = " UPDATE lash SET nombre = '".$_POST['nombre']."'";
    $respuesta = mysqli_query($conexion, $peticion);
    header("location:olvidar.php");
    
    ?>