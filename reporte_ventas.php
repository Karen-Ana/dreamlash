<?php  
session_start();

// Conexión a la base de datos
$host = "localhost";
$user = "anak";
$password = "525486Ak";
$database = "coffy";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se enviaron las fechas para el rango del reporte
if (isset($_POST['mes_inicio']) && isset($_POST['mes_fin'])) {
    $mesInicio = $_POST['mes_inicio'] . "-01"; // Primer día del mes de inicio
    $mesFin = date("Y-m-t", strtotime($_POST['mes_fin'] . "-01")); // Último día del mes de fin

    // Consulta para obtener el reporte de ventas dentro del rango de fechas seleccionado
    $sql = "SELECT 
                p.nombre AS producto, 
                COUNT(c.id) AS cantidad_vendida, 
                SUM(c.cantidad * p.precio) AS ingresos_totales,
                DATE(c.fecha) AS fecha_venta
            FROM 
                ventas c
            JOIN 
                productos p ON c.producto_id = p.id
            WHERE 
                c.fecha BETWEEN '$mesInicio' AND '$mesFin'
            GROUP BY 
                p.nombre, DATE(c.fecha)
            ORDER BY 
                fecha_venta DESC, cantidad_vendida DESC";

    $result = $conn->query($sql);

    // Consulta para obtener los totales generales en el rango de fechas seleccionado
    $sqlTotales = "SELECT 
                        SUM(c.cantidad) AS total_cantidad,
                        SUM(c.cantidad * p.precio) AS total_ingresos
                    FROM 
                        ventas c
                    JOIN 
                        productos p ON c.producto_id = p.id
                    WHERE 
                        c.fecha BETWEEN '$mesInicio' AND '$mesFin'";

    $resultTotales = $conn->query($sqlTotales);
    $totales = $resultTotales->fetch_assoc();
} 

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - MyStoreComicxManga</title>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Contenedor principal */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            padding: 20px;
        }

        /* Encabezado */
        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        /* Formulario */
        form {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }

        form label {
            font-weight: bold;
            margin-right: 5px;
        }

        form input[type="month"] {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #eef;
        }

        /* Totales Generales */
        .totales-generales {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
        }

        .totales-generales h3 {
            margin-top: 0;
            color: #007bff;
        }

        /* Botón de regreso */
        button {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Reporte de Ventas</h1>
        </header>

        <!-- Formulario para seleccionar el rango de fechas -->
        <form method="post">
            <label for="mes_inicio">Mes de inicio (YYYY-MM):</label>
            <input type="month" name="mes_inicio" required>
            
            <label for="mes_fin">Mes de fin (YYYY-MM):</label>
            <input type="month" name="mes_fin" required>
            
            <input type="submit" value="Generar Reporte">
        </form>

        <!-- Mostrar el reporte solo si hay datos de ventas -->
        <?php if (isset($result) && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad Vendida</th>
                        <th>Ingresos Totales</th>
                        <th>Fecha de Venta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['producto']) ?></td>
                            <td><?= htmlspecialchars($row['cantidad_vendida']) ?></td>
                            <td>$<?= number_format($row['ingresos_totales'], 2) ?></td>
                            <td><?= htmlspecialchars($row['fecha_venta']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Mostrar totales generales -->
            <div class="totales-generales">
                <h3>Totales Generales</h3>
                <p>Total de productos vendidos: <?= number_format($totales['total_cantidad'], 0) ?></p>
                <p>Ingresos totales: $<?= number_format($totales['total_ingresos'], 2) ?></p>
            </div>
        <?php elseif (isset($result)): ?>
            <p>No hay datos de ventas disponibles para el período seleccionado.</p>
        <?php endif; ?>

        <button onclick="window.location.href='principal.php'">Regresar</button>
    </div>
</body>
</html>
