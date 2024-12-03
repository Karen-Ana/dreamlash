<?php
require_once('fpdf.php'); // Asegúrate de que la ruta es correcta
session_start();
include 'config.php'; // Conexión a la base de datos

$productosCarrito = $_SESSION['carrito'];
$total = 0;
date_default_timezone_set('America/Mexico_City'); // Establece la zona horaria de la Ciudad de México
$fechaCompra = date("Y-m-d H:i:s"); // Obtener fecha y hora actuales

// Definir nombre de la empresa
$nombreEmpresa = "Dream Lash"; // Cambia esto por el nombre real de tu empresa

// Crear nuevo PDF
$pdf = new FPDF();
$pdf->AddPage();

// Mostrar nombre de la empresa
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 10, $nombreEmpresa, 0, 1, 'C');
$pdf->Ln(5); // Espacio entre nombre de empresa y el título

// Título del ticket
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Ticket de Compra', 0, 1, 'C');
$pdf->Ln(10);

// Mostrar fecha y hora de la compra
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(0, 10, 'Fecha de Compra: ' . $fechaCompra, 0, 1);
$pdf->Ln(5);

// Encabezado de la tabla con nueva columna para la imagen
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(40, 40, 'Imagen', 1, 0, 'C');
$pdf->Cell(40, 40, 'Producto', 1, 0, 'C');
$pdf->Cell(40, 40, 'Cantidad', 1, 0, 'C');
$pdf->Cell(40, 40, 'Precio', 1, 0, 'C');
$pdf->Cell(40, 40, 'Subtotal', 1, 1, 'C');

// Agregar los productos al PDF
foreach ($productosCarrito as $id => $cantidad) {
    $sql = "SELECT nombre, precio, imagen FROM productos WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();
    
    if ($producto) {
        $subtotal = $producto['precio'] * $cantidad;
        $total += $subtotal; // Sumar al total

        // Verificar que se obtenga el BLOB de la imagen
        if (!empty($producto['imagen'])) {
            // Guardar el BLOB en un archivo temporal
            $tempImgPath = 'temp_image_' . uniqid() . '.jpg'; // Nombre único para la imagen temporal
            file_put_contents($tempImgPath, $producto['imagen']); // Guardar el BLOB en un archivo

            // Incluir la imagen en la primera columna de la fila
            $pdf->Cell(40, 40, '', 1, 0, 'C');
            $pdf->Image($tempImgPath, $pdf->GetX() - 40, $pdf->GetY() + 5, 30, 30); // Ajusta el tamaño de la imagen
            unlink($tempImgPath); // Eliminar el archivo temporal después de usarlo
        } else {
            $pdf->Cell(40, 40, 'No disponible', 1, 0, 'C');
        }

        // Mostrar nombre del producto, cantidad, precio y subtotal en las otras celdas
        $pdf->Cell(40, 40, $producto['nombre'], 1);
        $pdf->Cell(40, 40, $cantidad, 1, 0, 'C');
        $pdf->Cell(40, 40, '$' . number_format($producto['precio'], 2), 1, 0, 'C');
        $pdf->Cell(40, 40, '$' . number_format($subtotal, 2), 1, 1, 'C');
    }
}

// Añadir total general
$pdf->Ln(10);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(160, 10, 'Total de la compra:', 0, 0, 'R');
$pdf->Cell(40, 10, '$' . number_format($total, 2), 1, 1, 'C');

// Cerrar y descargar el PDF
$pdf->Output('ticket_compra.pdf', 'D');
?>
