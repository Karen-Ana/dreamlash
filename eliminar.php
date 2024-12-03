<?php
// Conexión a la base de datos
include 'config.php';

// Verificamos si se ha enviado el ID y si la solicitud es POST
if (isset($_GET['id']) && isset($_POST['confirmar'])) {
    $id = $_GET['id'];

    // Consulta para eliminar el producto
    $sql = "DELETE FROM productos WHERE id = $id";
    if ($conexion->query($sql) === TRUE) {
        echo "Producto eliminado correctamente.";
    } else {
        echo "Error al eliminar el producto: " . $conexion->error;
    }

    // Redireccionar al listado de productos después de 2 segundos
    header("refresh:2;url=principal.php");
    exit;
} elseif (isset($_GET['id'])) {
    // Si solo se ha recibido el ID, mostramos la confirmación
    $id = $_GET['id'];
    echo "
    <script>
        var confirmar = confirm('¿Estás seguro de que deseas eliminar este producto?');
        if (confirmar) {
            document.getElementById('formularioEliminar').submit();
        } else {
            window.location.href = 'principal.php'; // Redirige si se cancela
        }
    </script>
    ";
}
?>

<!-- Formulario oculto para eliminar el producto -->
<form id="formularioEliminar" method="POST">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="confirmar" value="true">
</form>
