<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    
</head>
<body>

    <div id="formulario-producto" class="login-container">
    <h1>Agregar Producto</h1>
        <form action="proc_produc.php" method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>

            <label for="precio">Precio:</label>
            <input type="text" id="precio" name="precio" required step="0.01"><br><br>

            <label for="cantidad">Cantidad:</label>
            <input type="text" id="cantidad" name="cantidad" required><br><br>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required><br><br>

            <input type="submit" value="Agregar">
        </form>
        <a href="principal.php" class="register-button">Regresar</a>
    </div>

</body>
</html>


