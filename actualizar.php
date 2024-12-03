<?php
include 'config.php'; // Archivo de conexión

// Verificar si se ha enviado el ID del producto
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obtener los datos del producto con el ID proporcionado
    $query = "SELECT nombre, precio, cantidad, vendidos FROM productos WHERE id = ?"; // Agregando 'vendidos'
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener los datos del producto
        $row = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "ID del producto no proporcionado.";
    exit();
}

// Verificar si se ha enviado el formulario para actualizar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $vendidos = $_POST['vendidos']; // Recibir el nuevo campo

    // Consulta para actualizar los datos del producto
    $update_query = "UPDATE productos SET nombre = ?, precio = ?, cantidad = ?, vendidos = ? WHERE id = ?";
    $stmt = $conexion->prepare($update_query);
    $stmt->bind_param("ssdii", $nombre, $precio, $cantidad, $vendidos, $id); // Cambiar los tipos según corresponda

    if ($stmt->execute()) {
        // Redirigir de nuevo a la lista de productos
        header("Location: principal.php");
        exit();
    } else {
        echo "Error al actualizar los datos: " . $conexion->error;
    }

    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #6A0DAD; /* Morado */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: white;
            width: 300px;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        input[type="text"] {
            background-color: white;
            color: black;
        }

        input[type="submit"] {
            background-color: #4CAF50; /* Verde */
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .register-button {
            display: inline-block;
            background-color: #d9534f; /* Rojo */
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .register-button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form method="POST">
        <h2>Actualizar producto</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($row['nombre']); ?>" required>

        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio" value="<?php echo htmlspecialchars($row['precio']); ?>" required>

        <label for="cantidad">Cantidad:</label>
        <input type="text" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($row['cantidad']); ?>" required>

        <label for="vendidos">Vendidos:</label>
        <input type="text" id="vendidos" name="vendidos" value="<?php echo htmlspecialchars($row['vendidos']); ?>" required> <!-- Nuevo campo para los vendidos -->

        <input type="submit" value="Actualizar">
        <a href="principal.php" class="register-button">Regresar</a>
    </form>
</div>

</body>
</html>
