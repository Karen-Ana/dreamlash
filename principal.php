<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DREAM LASH</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #b233d1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #bc2dc4;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        img {
            width: 50px;
            height: 50px;
        }

        a {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #45a049;
        }

        button {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<h1>¡Bienvenido a DREAM LASH!</h1>

<?php
// Conexión a la base de datos
include 'config.php';

$sql = "SELECT id, nombre, precio, cantidad, vendidos, imagen FROM productos"; // Asegúrate de que 'vendidos' esté en la tabla
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Vendidos</th> <!-- Nueva columna para los productos vendidos -->
                <th>Imagen</th>
                <th>Agregar</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>";
            while ($fila = $result->fetch_assoc()) {
                // Comprobar si la imagen existe y es un BLOB
                if (!empty($fila['imagen'])) {
                    $imagenBase64 = base64_encode($fila['imagen']);
                    $srcImagen = "data:image/jpeg;base64," . $imagenBase64;
                } else {
                    $srcImagen = "ruta/a/imagen/default.jpg"; // Imagen por defecto
                }
        
                echo "<tr>
                        <td>{$fila['id']}</td>
                        <td>{$fila['nombre']}</td>
                        <td>\${$fila['precio']}</td>
                        <td>{$fila['cantidad']}</td>
                        <td>{$fila['vendidos']}</td> <!-- Mostrar la cantidad de vendidos -->
                        <td><img src='$srcImagen' alt='Imagen del producto'></td>
                        <td><a href='agregar.php?id={$fila['id']}'>Agregar</a></td>
                        <td><a href='actualizar.php?id={$fila['id']}'>Actualizar</a></td>
                        <td><a href='eliminar.php?id={$fila['id']}'>Eliminar</a></td>
                        </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }
        
$conexion->close();
?>

<a href="destruir.php"><button>Cerrar Sesión</button></a>
<a href="grafica.php"><button>Grafica</button></a>
<a href="reporte_ventas.php"><button>Generar reporte</button></a>
<a href="3d.php"><button>Figura 3D</button></a>
<a href="productos.php"><button>Carrito de compra</button></a>

</body>
</html>
