<?php
session_start();
include 'config.php'; // Conexión a la base de datos
require 'fpdf.php'; // Incluir FPDF

// Asegúrate de que la opción de ticket está seleccionada
if (isset($_POST['opcion_ticket'])) {
    $opcion_ticket = $_POST['opcion_ticket'];

    // Obtener detalles de productos del carrito
    if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        $productos = $_SESSION['carrito'];
        $total = 0;
        foreach ($productos as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }
        $fecha = date("Y-m-d H:i:s");

        // Generar el PDF
        if ($opcion_ticket === 'pdf') {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Ticket de Compra', 0, 1, 'C');
            $pdf->Ln(10);

            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, "Fecha de Compra: $fecha", 0, 1);
            $pdf->Ln(5);
            $pdf->Cell(0, 10, "Total de la Compra: $$total", 0, 1);
            $pdf->Ln(10);

            foreach ($productos as $producto) {
                $nombreProducto = $producto['nombre'];
                $pdf->Cell(0, 10, "Producto: " . $nombreProducto, 0, 1);
                $pdf->Cell(0, 10, "Cantidad: " . $producto['cantidad'], 0, 1);
                $pdf->Cell(0, 10, "Precio por pieza: $" . number_format($producto['precio'], 2), 0, 1);
                $pdf->Ln(5);

                // Consultar la imagen del producto mediante el nombre
                $query = "SELECT imagen FROM productos WHERE nombre = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $nombreProducto);
                $stmt->execute();
                $result = $stmt->get_result();
                $imgData = $result->fetch_assoc();

                // Verifica que se obtenga el BLOB de la imagen
                if ($imgData && isset($imgData['imagen'])) {
                    // Guardar el BLOB en un archivo temporal
                    $tempImgPath = 'temp_image_' . uniqid() . '.jpg'; // Nombre único para la imagen temporal
                    file_put_contents($tempImgPath, $imgData['imagen']); // Guardar el BLOB en un archivo

                    // Incluir la imagen en el PDF
                    $pdf->Image($tempImgPath, null, null, 40, 40); // Ajusta el tamaño según necesites
                    $pdf->Ln(5);

                    // Eliminar el archivo temporal después de usarlo
                    unlink($tempImgPath);
                } else {
                    $pdf->Cell(0, 10, "Imagen no disponible.", 0, 1);
                }
            }

            // Guardar PDF en el servidor
            $pdfFilePath = 'tickets/ticket_compra_' . uniqid() . '.pdf';
            $pdf->Output('F', $pdfFilePath); // Guardar en el servidor

            // Mostrar el PDF
            echo '<h2>Tu ticket ha sido generado:</h2>';
            echo '<a href="' . $pdfFilePath . '" target="_blank">Descargar Ticket en PDF</a>';
            echo '<iframe src="' . $pdfFilePath . '" width="100%" height="600px"></iframe>';
            echo '<br><a href="realizar_compra.php"><button>Regresar a Realizar Compra</button></a>'; // Botón para regresar a Realizar Compra
            exit;
        }
    } else {
        echo "No hay productos en el carrito.";
    }
} else {
    echo "Seleccione una opción válida para el ticket.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Compra</title>
</head>
<body>
    <h2>Opciones de Ticket</h2>
    <form action="realizar_compra.php" method="post">
        <?php 
        // Cálculo del total de la compra
        $totalCompra = 0;
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $producto) {
                $totalCompra += $producto['precio'] * $producto['cantidad'];
            }
        }
        ?>
        <p>Total de la compra: $<span id="totalCompra"><?= number_format($totalCompra, 2); ?></span></p>
        <input type="hidden" name="total" value="<?= $totalCompra; ?>">

        <label>
            <input type="radio" name="opcion_ticket" value="pdf" required>
            Descargar en PDF
        </label><br>

        <button type="submit">Realizar compra</button>
    </form>

    <br>
    <form action="carrito.php">
        <button type="submit">Regresar a Carrito</button>
    </form>
</body>
</html>