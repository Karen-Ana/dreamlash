<?php
// Conexión a la base de datos
include 'config.php';

// Consulta para obtener los productos
$sql = "SELECT id, nombre, precio, cantidad, imagen FROM productos"; // Asegúrate de que 'precio' y 'imagen' estén en la tabla
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1 {
            color: #6a0dad; /* Color lila */
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #6a0dad; /* Color lila para el encabezado */
            color: white;
        }

        tr:hover {
            background-color: #f1e6f7; /* Color suave al pasar el mouse */
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #6a0dad; /* Color lila para los botones */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #4e0a8b; /* Color más oscuro al pasar el mouse */
        }

        a {
            text-decoration: none;
            margin: 10px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function actualizarCantidad(id, maxCantidad) {
            const cantidad = document.getElementById('cantidad-' + id).value;
            if (cantidad > maxCantidad) {
                alert('La cantidad no puede ser mayor a ' + maxCantidad);
                document.getElementById('cantidad-' + id).value = maxCantidad;
            }
        }

        function agregarAlCarrito(id) {
            const cantidad = document.getElementById('cantidad-' + id).value;
            if (cantidad > 0) {
                window.location.href = 'agregar_carrito.php?id=' + id + '&cantidad=' + cantidad;
            } else {
                alert('La cantidad debe ser mayor a 0.');
            }
        }
    </script>
</head>
<body>
    <h1>Lista de Productos</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre del Producto</th>
            <th>Cantidad en Existencia</th>
            <th>Imagen</th>
            <th>Precio (MXN)</th>
            <th>Seleccionar Cantidad</th>
            <th>Acción</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Verifica si la imagen existe
                if (!empty($row['imagen'])) {
                    $imagenBase64 = base64_encode($row['imagen']);
                    $srcImagen = "data:image/jpeg;base64," . $imagenBase64;
                } else {
                    $srcImagen = "ruta/a/imagen/default.jpg"; // Imagen por defecto
                }
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                    <td><img src="<?php echo $srcImagen; ?>" alt="Imagen del producto" width="50" height="50"></td>
                    <td>$<?php echo htmlspecialchars($row['precio']); ?> MXN</td>
                    <td>
                        <input type="number" id="cantidad-<?php echo $row['id']; ?>" min="1" max="<?php echo $row['cantidad']; ?>" value="1" oninput="actualizarCantidad(<?php echo $row['id']; ?>, <?php echo $row['cantidad']; ?>)">
                    </td>
                    <td>
                        <button onclick="agregarAlCarrito(<?php echo $row['id']; ?>)">Agregar al Carrito</button>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='7'>No hay productos disponibles</td></tr>";
        }
        $conexion->close();
        ?>
    </table>
    <a href="carrito.php" style="color: #6a0dad;">Ver Carrito</a><br>
    <a href="destruir.php"><button>Cerrar Sesión</button></a>
</body>
</html>
