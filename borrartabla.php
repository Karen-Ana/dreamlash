<?php 

include"./../pages/config.php";

$opcion = $_GET['op']; 
switch ($opcion) {
    case 1:
        $peticion = "TRUNCATE TABLE lash";
        $respuesta = mysqli_query($conexion, $peticion);
        session_start();
        session_destroy();
        header("location:olvidar.php");
        break;
   
    
    default:
        session_start();
        session_destroy();
        break;
}

?>