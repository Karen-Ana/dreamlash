<?php
session_start();

// Verificar que se envíen el ID y la cantidad
if (isset($_GET['id']) && isset($_GET['cantidad'])) {
    $id = $_GET['id'];
    $cantidad = (int)$_GET['cantidad'];

    // Inicializar el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Agregar o actualizar la cantidad del producto en el carrito
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id] += $cantidad;
    } else {
        $_SESSION['carrito'][$id] = $cantidad;
    }
    
    // Redirigir de vuelta a la página de productos
    header('Location: productos.php');
    exit();
}
?>
