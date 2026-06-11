<?php
session_start();

// El control de acceso.
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'empleado') {
    header('Location: ../index.php');
    exit();
}

require '../config/db.php';

// Variable de sesión del carrito - inicializar el carrito.
if(!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];    
}

if(isset($_GET['agregar'])) {
    $id = $_GET['agregar'];
    // Verificarq que el producto exista y tenga stock > 1.
    $stmt = $pdo->prepare("SELECT * FROM `productos` WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    if($producto && $producto['stock'] > 0) {
        // Si le producto ya existe en el carrito, aumenta la cantidad.
        if(isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad']++;
        } else {
            // Si es nuevo en el carrito.
            $_SESSION['carrito'][$id] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => 1
            ];
        }
    }
}

// Consulta de productos a la BD.
$stmt = $pdo->query("SELECT * FROM `productos` WHERE  stock > 0");
$productos = $stmt->fetchAll();

include '../templates/header.php';
?>
<div class="contenedor-catalogo-encabezado">
    <h2>Catálogo de Ropa</h2>
    <a href="carrito.php" class="btn-carrito">Ver carrito (<?php echo array_sum(array_column($_SESSION['carrito'], 'cantidad')); ?>)</a>

</div>
<div class="contenedor-catalogo-detalle">
    <?php foreach ($productos as $p): ?>
    <div class="producto">
        <img src="../uploads/<?php echo $p['imagen'] ?>" class="producto-imagen">
        <h4 class="producto-nombre"><?php echo $p['nombre']; ?></h4>
        <p class="producto-precio">$<?php echo number_format($p['precio'], 0); ?></p>
        <p class="producto-stock">Disponibles: <?php echo $p['stock']; ?></p>
        <a class="btn-agregar" href="?agregar=<?php echo $p['id']; ?>">Agregar al carrito</a>
    </div>
    <?php endforeach; ?>
</div>
<?php
include '../templates/footer.php';
?>