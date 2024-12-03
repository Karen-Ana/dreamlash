<?php
session_start();

// Verificar si hay productos en el carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Manejar eliminación de un producto
if (isset($_GET['eliminar'])) {
    $idEliminar = $_GET['eliminar'];
    if (isset($_SESSION['carrito'][$idEliminar])) {
        unset($_SESSION['carrito'][$idEliminar]);
    }
}

// Manejar modificación de la cantidad de un producto
if (isset($_POST['modificar'])) {
    $idModificar = $_POST['id'];
    $nuevaCantidad = intval($_POST['cantidad']);
    if ($nuevaCantidad > 0) {
        $_SESSION['carrito'][$idModificar] = $nuevaCantidad;
    } else {
        unset($_SESSION['carrito'][$idModificar]);
    }
}

$productosCarrito = $_SESSION['carrito'];
$total = 0; // Inicializa el total
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <script>
        function confirmarEliminar(id) {
            if (confirm('¿Estás seguro de eliminar este producto del carrito?')) {
                window.location.href = 'carrito.php?eliminar=' + id;
            }
        }
    </script>
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

        a {
            text-decoration: none;
            color: #6a0dad; /* Color lila para los enlaces */
            margin: 5px;
            display: inline-block;
        }

        .eliminar {
            color: #e74c3c; /* Color rojo para eliminar */
            cursor: pointer;
        }

        .eliminar:hover {
            text-decoration: underline;
        }

        .total {
            font-weight: bold;
            margin-top: 20px;
            font-size: 20px;
        }

        .button {
            background-color: #6a0dad; /* Color lila para el botón */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #4e0a8b; /* Color más oscuro al pasar el mouse */
        }

        input[type="number"] {
            width: 50px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #6a0dad;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #4e0a8b;
        }
    </style>
</head>
<body>
    <h1>Productos en el Carrito</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre del Producto</th>
            <th>Cantidad</th>
            <th>Imagen</th>
            <th>Precio (MXN)</th>
            <th>Acción</th>
        </tr>
        <?php
        if (empty($productosCarrito)) {
            echo "<tr><td colspan='6'>El carrito está vacío</td></tr>";
        } else {
            include 'config.php'; // Necesario para obtener nombres, imágenes y precios de productos
            foreach ($productosCarrito as $id => $cantidad) {
                $sql = "SELECT nombre, precio, imagen FROM productos WHERE id = $id"; 
                $resultado = $conexion->query($sql);
                $producto = $resultado->fetch_assoc();

                if (!empty($producto['imagen'])) {
                    $imagenBase64 = base64_encode($producto['imagen']);
                    $srcImagen = "data:image/jpeg;base64," . $imagenBase64;
                } else {
                    $srcImagen = "ruta/a/imagen/default.jpg"; // Imagen por defecto
                }

                $subtotal = $producto['precio'] * $cantidad; 
                $total += $subtotal; 
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($id); ?></td>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td>
                        <form method="POST" action="carrito.php" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="number" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" min="1">
                            <input type="submit" name="modificar" value="Modificar">
                        </form>
                    </td>
                    <td><img src="<?php echo $srcImagen; ?>" alt="Imagen del producto" width="50" height="50"></td>
                    <td>$<?php echo htmlspecialchars($producto['precio']); ?> MXN</td>
                    <td>
                        <span class="eliminar" onclick="confirmarEliminar('<?php echo $id; ?>')">Eliminar</span>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>

    <h2 class="total">Total a Pagar: $<?php echo htmlspecialchars($total); ?> MXN</h2> 

    <a href="productos.php" class="button">Volver a Productos</a>
    <a href="pagar.php" class="button">Pagar Productos</a>
</body>
</html>
