<?php
session_start();

// El control de acceso.
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'empleado') {
    header('Location: ../index.php');
    exit();
}

require '../config/db.php';

// Procesamiendo de vaciado del carrito.
if(isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
    header('Location: carrito.php');
}

// Procesamiento de quitar items.
if(isset($_GET['quitar'])) {
    $id = $_GET['quitar'];
    unset($_SESSION['carrito'][$id]);
    header('Location: carrito.php');
}

include '../templates/header.php';
?>

<h2 class="text-center" style="margin-top:100px; padding-top: 30px;">Carrito de compras</h2>

<?php if(empty($_SESSION['carrito'])): ?>
<p>El carrito está vacío <a class="btn-volver" href="catalogo.php" >Volver al catálogo</a></p>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr class="text-center">
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['carrito'] as $id => $item):
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;
            ?>
            <tr class="text-center">
                <td><?php echo $item['nombre']; ?></td>
                <td>$ <?php echo number_format($item['precio']); ?></td>
                <td><?php echo $item['cantidad']; ?></td>
                <td>$ <?php echo number_format($subtotal); ?></td>
                <td><a href="?quitar=<?php echo $id; ?>" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i> Borrar</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="text-end td-total" colspan="3">TOTAL A PAGAR:</td>
                <td class="text-center td-valor">$ <?php echo number_format($total); ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="contenedor-botones">
    <a href="catalogo.php" class="btn btn-sm btn-seguir"><i class="bi bi-arrow-left"></i> Seguir comprando</a>
    <a href="?vaciar=1" class="btn btn-sm btn-vaciar"><i class="bi bi-trash2-fill"></i> Vaciar el carrito</a>
    <form action="procesar_venta.php" method="post" style="display: inline;">
        <input type="hidden" name="total_venta" value="<?php echo $total; ?>">
        <button type="submit" class="btn btn-sm btn-generar"><i class="bi bi-receipt"></i> Generar factura</button>
    </form>
</div>
   
<?php endif; ?>

<?php
include '../templates/footer.php';
?>