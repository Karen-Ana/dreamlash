<?php
// Incluir el archivo de conexión
include 'config.php';

// Verificar que los datos se envíen por el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $imagen = $_FILES['imagen'];

    // Verificar que la imagen se haya subido correctamente
    if (is_uploaded_file($imagen['tmp_name'])) {
        // Convertir la imagen en datos binarios (BLOB)
        $imagenBinaria = file_get_contents($imagen['tmp_name']);

        // Preparar la consulta SQL para insertar los datos en la base de datos
        $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio, cantidad, imagen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdis", $nombre, $precio, $cantidad, $imagenBinaria);

        // Ejecutar la consulta y verificar si se realizó con éxito
        if ($stmt->execute()) {
            header('Location: principal.php');
            exit;
        } else {
            echo "Error al agregar el producto: " . $stmt->error;
        }

        // Cerrar el statement
        $stmt->close();
    } else {
        echo "Error: No se pudo cargar la imagen.";
    }
} else {
    echo "Método no permitido.";
}

// Cerrar la conexión
$conexion->close();
?>
