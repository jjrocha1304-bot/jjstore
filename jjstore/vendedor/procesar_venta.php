<?php
session_start();

// El control de acceso.
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'empleado') {
    header('Location: ../index.php');
    exit();
}

require '../config/db.php';

$id_vendedor = $_SESSION['user_id'];
$total_venta = $_POST['total_venta'];

try {
    // Insertas los datos en la tabla de las ventas.
    $sql_venta = "INSERT INTO `ventas`(`total`, `id_vendedor`) VALUES (?, ?)";
    $stmt_venta = $pdo->prepare($sql_venta);
    $stmt_venta->execute([$total_venta, $id_vendedor]);

    // Capturar el id de la factura.
    $id_factura = $pdo->lastInsertId();

    // Recorrer todos los productos del carrito y el manejo del stock.
    foreach ($_SESSION['carrito'] as $id_producto => $item) {
        $cantidad = $item['cantidad'];
        $precio = $item['precio'];

        $sql_detalle = "INSERT INTO `detalle_ventas`(`id_venta`, `id_producto`, `cantidad`, `precio_unitario`) VALUES (?, ?, ?, ?)";
        $stmt_detalle = $pdo->prepare($sql_detalle);
        $stmt_detalle->execute([$id_factura, $id_producto, $cantidad, $precio]);

        // Modificación del stock de productos.
        $sql_stock = "UPDATE `productos` SET `stock`= stock - ? WHERE id = ?";
        $stmt_stock = $pdo->prepare($sql_stock);
        $stmt_stock->execute([$cantidad, $id_producto]);
    }

    // Vaciar el carrito.
    $_SESSION['carrito'] = [];
    header('Location: factura.php?id=' . $id_factura);



} catch (Excentio $e) {
    // Esto es por si algo falla durante el procesamiento de la venta.
    echo "Error en el procesamiento de la venta: " . $e->getMessage();
}

?>