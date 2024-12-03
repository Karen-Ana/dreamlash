<?php
session_start();
include 'config.php'; // Conexión a la base de datos
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';
require 'fpdf.php'; // Incluir FPDF

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$productosCarrito = $_SESSION['carrito'];
$total = 0;

// Calcular el total
foreach ($productosCarrito as $id => $cantidad) {
    $sql = "SELECT precio FROM productos WHERE id = $id"; 
    $resultado = $conexion->query($sql);
    $producto = $resultado->fetch_assoc();
    $total += $producto['precio'] * $cantidad;
}

// Función para generar el PDF del ticket
function generarTicketPDF($productosCarrito, $total, $conexion) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Ticket de Compra', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(10);
    
    // Crear tabla en PDF
    $pdf->SetFillColor(200, 220, 255);
    $pdf->Cell(70, 40, 'Producto', 1, 0, 'C', true);
    $pdf->Cell(30, 40, 'Cantidad', 1, 0, 'C', true);
    $pdf->Cell(30, 40, 'Subtotal', 1, 0, 'C', true);
    $pdf->Cell(40, 40, 'Imagen', 1, 1, 'C', true); // Encabezado de imagen

    foreach ($productosCarrito as $id => $cantidad) {
        $sql = "SELECT nombre, precio, imagen FROM productos WHERE id = $id"; 
        $resultado = $conexion->query($sql);
        $producto = $resultado->fetch_assoc();
        $subtotal = $producto['precio'] * $cantidad;
        
        // Mostrar imagen en PDF
        $tempImgPath = 'ruta/a/imagen/default.jpg'; // Imagen por defecto
        if (!empty($producto['imagen'])) {
            $tempImgPath = 'temp_image_' . uniqid() . '.jpg';
            file_put_contents($tempImgPath, $producto['imagen']);
        }

        // Agregar los datos del producto
        $pdf->Cell(70, 40, $producto['nombre'], 1);
        $pdf->Cell(30, 40, $cantidad, 1);
        $pdf->Cell(30, 40, "$$subtotal", 1);
        
        // Colocar la celda para la imagen y añadir la imagen en la misma fila
        $pdf->Cell(40, 40, '', 1); // Espacio para la celda de imagen
        $pdf->Image($tempImgPath, $pdf->GetX() - 40, $pdf->GetY() - 40, 40, 40); // Ajustar el tamaño de la imagen
        $pdf->Ln(0); // Mantener en la misma línea

        // Para la próxima fila
        $pdf->Ln(40); // Hacer un salto de línea para la siguiente fila
    }

    $pdf->Ln(10);
    $pdf->Cell(0, 10, "Total: $$total", 0, 1);

    $pdfFilePath = 'ticket_compra_' . time() . '.pdf';
    $pdf->Output($pdfFilePath, 'F'); // Guardar en el servidor temporalmente
    return $pdfFilePath;
}

// Función para enviar el ticket por correo
function enviarTicketPorCorreo($email, $pdfFilePath) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'anacasiano.kp@gmail.com';
        $mail->Password = 'pqim ijvb lfik goan';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('anacasiano.kp@gmail.com', 'Tu Empresa');
        $mail->addAddress($email);
        $mail->Subject = 'Tu Ticket de Compra';
        $mail->Body = 'Gracias por tu compra. Adjunto encontrarás tu ticket de compra en PDF.';

        // Adjuntar el PDF al correo
        $mail->addAttachment($pdfFilePath);
        $mail->send();
        echo "<script>alert('Ticket enviado a $email');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error al enviar el correo: {$mail->ErrorInfo}');</script>";
    }
}

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $tipo = $_POST['tipo'] ?? '';

    // Generar el PDF solo una vez
    $pdfFilePath = generarTicketPDF($productosCarrito, $total, $conexion);

    if ($tipo === 'correo' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        enviarTicketPorCorreo($email, $pdfFilePath);
    } elseif ($tipo === 'pdf') {
        // Redirigir a la descarga del PDF generado
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=" . basename($pdfFilePath));
        readfile($pdfFilePath);
        exit();
    } else {
        echo "<script>alert('Por favor, proporciona un correo válido.');</script>";
    }

    // Eliminar el archivo PDF temporal después de enviar
    if (file_exists($pdfFilePath)) {
        unlink($pdfFilePath);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        h1 { color: #6a0dad; }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #6a0dad;
            color: white;
        }
        form {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
        }
        input[type="email"], input[type="submit"] {
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: calc(100% - 22px);
        }
        input[type="submit"] {
            background-color: #6a0dad;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #4e0a8b;
        }
    </style>
</head>
<body>
    <h1>Resumen de Compra</h1>
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Imagen</th>
        </tr>
        <?php
        foreach ($productosCarrito as $id => $cantidad) {
            // Obtener nombre e imagen del producto
            $sql = "SELECT nombre, imagen FROM productos WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $producto = $result->fetch_assoc();

            // Verificar y mostrar imagen temporal
            $srcImagen = 'ruta/a/imagen/default.jpg'; // Imagen por defecto
            if ($producto && !empty($producto['imagen'])) {
                $tempImgPath = 'temp_image_' . uniqid() . '.jpg';
                file_put_contents($tempImgPath, $producto['imagen']);
                $srcImagen = $tempImgPath;
            }



            echo "<tr>
                    <td>{$producto['nombre']}</td>
                    <td>$cantidad</td>
                    <td><img src='$srcImagen' alt='Imagen del producto' width='50' height='50'></td>
                  </tr>";
        }
        ?>
    </table>
    <h2>Total: $<?php echo htmlspecialchars($total); ?></h2>



    <form method="POST">
        <label for="email">Tu Correo:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label>
            <input type="radio" name="tipo" value="correo" required> Enviar por Correo
        </label>
        <br>
        <label>
            <input type="radio" name="tipo" value="pdf" required> Descargar PDF
        </label>
        <br>
        <input type="submit" value="Pagar">
    </form>
</body>
</html>
