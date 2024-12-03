<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'coffy';
$username = 'anak';
$password = '525486Ak';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los productos y sus cantidades
    $stmt = $pdo->prepare("SELECT nombre, cantidad, vendidos FROM productos");
    $stmt->execute();

    $productos = [];
    $existencias = [];
    $vendidos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $productos[] = $row['nombre'];
        $existencias[] = $row['cantidad'] - $row['vendidos']; // Calculamos el stock actual
        $vendidos[] = $row['vendidos'];
    }
} catch (PDOException $e) {
    echo 'Error en la conexión: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfica de Productos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Alinea los elementos en columna */
            height: 100vh;
            margin: 0;
        }
        .chart-container {
            width: 50%; /* La mitad de la página */
            max-width: 600px; /* Ancho máximo para pantallas grandes */
        }
        canvas {
            width: 100% !important;
            height: auto !important;
        }
    </style>
</head>
<body>
    <div class="chart-container">
        <h2 style="text-align: center;">Gráfica de Productos en Existencia y Vendidos</h2>
        <canvas id="productosChart"></canvas>
    </div>

    <script>
        // Datos iniciales para la gráfica desde PHP a JavaScript
        const productos = <?php echo json_encode($productos); ?>;
        const existencias = <?php echo json_encode($existencias); ?>;
        const vendidos = <?php echo json_encode($vendidos); ?>;

        // Configuración inicial de la gráfica con Chart.js
        const ctx = document.getElementById('productosChart').getContext('2d');
        const productosChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productos,
                datasets: [
                    {
                        label: 'Existencia',
                        data: existencias,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Vendidos',
                        data: vendidos,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
