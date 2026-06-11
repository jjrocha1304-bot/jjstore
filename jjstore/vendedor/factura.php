<?php
session_start();

// El control de acceso.
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'empleado') {
    header('Location: ../index.php');
    exit();
}

require '../config/db.php';

// Recepción del ID de la factura a mostrar.
$id_venta = $_GET['id'];

// Consultar la tabla de ventas.
$stmt_v = $pdo->prepare("SELECT v.*, u.nombre AS vendedor 
                        FROM ventas v 
                        JOIN usuarios u ON v.id_vendedor = u.id 
                        WHERE v.id = ?");
$stmt_v->execute([$id_venta]);
$venta = $stmt_v->fetch();

// Consultar los detalles de la venta.
$stmt_d = $pdo->prepare("SELECT dv.*, p.nombre
                        FROM detalle_ventas dv 
                        JOIN productos p ON dv.id_producto = p.id 
                        WHERE dv.id_venta = ?");
$stmt_d->execute([$id_venta]);
$detalles = $stmt_d->fetchAll();

include '../templates/header.php';
?>
<div class="contenedor-factura" style="margin-top:150px; padding-top: 60px;">
    <h2 class="factura-encabezado">TICKET DE VENTA</h2>
    <a class="navbar-brand d-flex flex-column align-items-center mt-2" href="index.php">
        <img src="../imagenes/logo_JJ_Store.png" alt="Logo" width="60" height="60" class="border border-dark rounded-circle">
        <span class="ms-2 fs-5 fw-bold">Store</span>
    </a>    
    <hr>
    <p><strong>Factura N°: </strong><?php echo $venta['id']; ?></p>
    <p><strong>Fecha: </strong><?php echo $venta['fecha']; ?></p>
    <p><strong>Atendido por: </strong><?php echo $venta['vendedor']; ?></p>

    <table class="tabla-factura">
        <thead>
            <tr class="factura-encabezado">
                <th align="left">Producto</th>
                <th align="right">Cant.</th>
                <th align="right">Precio Unit.</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $d): ?>
            <tr>
                <td><?php echo $d['nombre']; ?></td>
                <td align="center"><?php echo $d['cantidad']; ?></td>
                <td align="center">$<?php echo number_format($d['precio_unitario']); ?></td>
                <td align="center">$<?php echo number_format($d['cantidad'] * $d['precio_unitario']); ?></td>    
               
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <h4 class="factura-total">Total: $<?php echo number_format($venta['total']); ?></h4>

    <div class="contendor-factura-botones">
        <button onclick="window.print()" class="factura-btn-imprimir btn btn-sm"><i class="bi bi-printer-fill"></i> Imprimir ticket</button>
        <a href="catalogo.php" class="factura-btn-volver btn btn-sm"><i class="bi bi-arrow-left"></i> Volver al Catálogo</a>
    </div>
</div>


<?php
include '../templates/footer.php';
?>