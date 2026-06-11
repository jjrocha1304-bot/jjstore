<?php

// Permiso de conexión a la BD.
require '../config/db.php';

session_start();

// El control de acceso.
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit();
}

// Procesamiento del guardado.
if(isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Procesamiento de la imagen.
    $nombre_imagen = $_FILES['imagen']['name'];
    $ruta_temporal = $_FILES['imagen']['tmp_name'];
    $carpeta_destino = "../uploads/" . $nombre_imagen;

    // Mover la imagen a la carpeta destino y guardar.
    if(move_uploaded_file($ruta_temporal, $carpeta_destino)) {
        // Inserción en la BD del registro.
        $stmt = $pdo->prepare("INSERT INTO `productos`(`nombre`, `precio`, `stock`, `imagen`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $precio, $stock, $nombre_imagen]);
        header('Location: productos.php');
    }
}

// Consulta de registros.
$stmt = $pdo->query("SELECT * FROM `productos`");
$productos = $stmt->fetchAll();

// Proceso de Eliminar registro.
if(isset($_GET['eliminar'])){
    $id = $_GET['eliminar'];

    // Buscar la imagen en la BD.
    $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    if($producto) {
        unlink("../uploads/" . $producto['imagen']);
        $stmt = $pdo->prepare("DELETE FROM `productos` WHERE id = ?");
        $stmt->execute([$id]);
    }
    header('Location: productos.php');
}

include '../templates/header.php';
?>

<h2 class="text-center" style="margin-top:120px; padding-top:50px;">Gestión Inventario de Ropa</h2>
<div class="card">
    <div class="card-header"><strong>Agregar nueva prenda</strong></div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="" aria-describedby="helpId" 
                    placeholder="Nombre de la prenda" autocomplete="off" required/>                
                </div>
                <div class="col-md-3 mb-3">
                    <label for="" class="form-label">Precio</label>
                    <input type="number" class="form-control" name="precio" id="" aria-describedby="helpId"
                    placeholder="Precio de la prenda" autocomplete="off" required />               
                </div>
                <div class="col-md-2 mb-3">
                    <label for="" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="" aria-describedby="helpId"
                    placeholder="Cantidad inicial" autocomplete="off" required />               
                </div>
                <div class="col-md-4 mb-3">
                    <label for="" class="form-label">Imagen</label>
                    <input type="file" class="form-control" name="imagen" id="" aria-describedby="helpId"
                    required />               
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-sm" name="agregar">
                        <i class="bi bi-floppy-fill"></i> Guardar en almacén
                    </button>                
                </div>
            </div>            
        </form>
    </div>
    <div class="card-footer text-body-secondary"></div>
</div>
<br>
<hr>
<h3 class="text-center mt-5 mb-5">Lista de Productos</h3>
<div class="table-responsive tabla-productos">
    <table class="display table table-bordered table-hover align-middle" id="myTable">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
            <tr>                
                <td><img src="../uploads/<?php echo $p['imagen']; ?>" width="50"></td>
                <td><?php echo $p['nombre']; ?></td>
                <td>$ <?php echo number_format($p['precio'], 0, ',', '.'); ?></td>
                <td><?php echo $p['stock']; ?></td>
                <td>
                    <a name="" id="" class="btn btn-success btn-sm" href="editar_productos.php?id=<?php echo $p['id']; ?>" role="button"><i class="bi bi-pen-fill"></i> Editar</a>                    
                    <a name="" id="" class="btn btn-danger btn-sm" href="?eliminar=<?php echo $p['id']; ?>" role="button"><i class="bi bi-trash-fill"></i> Eliminar</a>                    
                </td>
            </tr>
            <?php endforeach; ?>          
        </tbody>
    </table>
</div>

<?php
include '../templates/footer.php';
?>