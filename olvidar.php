<?php
// Conexión a la base de datos
include 'config.php';

// Verificar si se envían los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $new_email = $_POST['email'];
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Actualizar correo y contraseña
        $stmt = $conexion->prepare("UPDATE lash SET correo = ?, contrasena = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_email, $new_password, $id);

        if ($stmt->execute()) {
            echo "Datos actualizados exitosamente.";
        } else {
            echo "Error al actualizar: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Eliminar usuario
        $stmt = $conexion->prepare("DELETE FROM lash WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "Usuario eliminado exitosamente.";
        } else {
            echo "Error al eliminar: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conexion->close();
?>









<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar y Eliminar Usuario</title>
    <script>
        function confirmAction(action) {
            return confirm("¿Estás seguro de que deseas " + action + " este usuario?");
        }
    </script>
</head>
<body>
    <h1>Actualizar y Eliminar Usuario</h1>
    <form method="POST">
        <label for="id">ID de Usuario:</label>
        <input type="number" name="id" required>
        <br>
        <label for="email">Nuevo Correo:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Nueva Contraseña:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit" name="update" onclick="return confirmAction('actualizar');">Actualizar</button>
        <button type="submit" name="delete" onclick="return confirmAction('eliminar');">Eliminar</button>
    </form>


    <style>
table{
    border: 1px solid black;
    margin: 40px auto;
    width 90%;
}
table tr td{
    border: 1px solid black;
    text-align: center;
    padding: 10px 15px;
}

</style>
 
    <?php
     include "./../pages/config.php";
    $peticion = " SELECT * FROM lash";
    $respuesta = mysqli_query($conexion, $peticion);
    while($fila = mysqli_fetch_array($respuesta)){
    ?>
<?php
    }
    ?>


<table>
    <tr>
        <td>id</td>
        <td>Nombre</td>
        <td>correo</td>
        <td>Constraseña</td>
        <td>Banear</td>


</tr>
<?php
     include "./../pages/config.php";
    $peticion = "SELECT * FROM lash";
    $respuesta = mysqli_query($conexion, $peticion);
    while($fila = mysqli_fetch_array($respuesta)){
    ?>

<tr>
 <td><?php echo $fila['id'];?></td>
 <td><?php echo $fila['nombre'];?></td>
 <td><?php echo $fila['correo'];?></td>
 <td><?php echo $fila['contrasena'];?></td>
 <td><a href="banear.php?id=<?php echo $fila['id'];?>"><button>Banear</button></a></td>

</tr>
<?php
    }
    ?>

</table>









    <a href="destruir.php"><button>Cerrar_Sesion</a>

</body>
</html>
