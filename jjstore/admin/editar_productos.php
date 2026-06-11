<?php

// Permiso de conexión a la BD.
require '../config/db.php';

session_start();

// El control de acceso.
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit();
}

// Obtener el ID del producto actual.
if(!isset($_GET['id'])){
    header('Location: productos.php');
    exit();
}

$id = $_GET['id'];
$stm = $pdo->prepare("SELECT * FROM `productos` WHERE id = ?");
$stm->execute([$id]);
$producto = $stm->fetch();

// Procesamiento de la actualización.
if(isset($_POST['actualizar'])){
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $imagen_nombre = $_POST['imagen_actual'];

    // Si se ha seleccionado otra imagen.
    if(!empty($_FILES['imagen']['name'])) {
        if(file_exists("../uploads/" . $_POST['imagen_actual'])) {
            unlink("../uploads/" . $_POST['imagen_actual']);
        }
        $imagen_nombre = $_FILES['imagen']['name'];
        $ruta_temporal = $_FILES['imagen']['tmp_name'];
        move_uploaded_file($ruta_temporal, "../uploads/" . $imagen_nombre);
    }

    $sql = "UPDATE `productos` SET nombre = ?, precio = ?, stock = ?, imagen = ? WHERE  id = ?";
    $stm = $pdo->prepare($sql);
    $stm->execute([$nombre, $precio, $stock, $imagen_nombre, $id]);

    //Redirección a la página productos.
    header('Location: productos.php');
    exit();
}

include '../templates/header.php';
?>

<h2 class="mt-3 text-center">Editar Producto</h2>

<div class="card">
    <div class="card-header"></div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="imagen_actual" value="<?php echo $producto['imagen']; ?>"/>  
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="" aria-describedby="helpId" value="<?php echo $producto['nombre']; ?>" required autocomplete="off"/>                
                </div>
                <div class="col-md-4 mb-3">
                    <label for="" class="form-label">Precio</label>
                    <input type="text" class="form-control" name="precio" id="" aria-describedby="helpId" value="<?php echo $producto['precio']; ?>" required autocomplete="off"/>                
                </div>
                <div class="col-md-4 mb-3">
                    <label for="" class="form-label">Stock</label>
                    <input type="text" class="form-control" name="stock" id="" aria-describedby="helpId" value="<?php echo $producto['stock']; ?>" required autocomplete="off"/>                
                </div> 
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="" class="form-label">Imagen Actual</label><br>
                    <img src="../uploads/<?php echo $producto['imagen']; ?>" alt="" width="100" style="border-radius: 10px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="" class="form-label">Seleccionar Nueva Imagen (Dejar vacio para mantener la actual)</label>
                    <input type="file" class="form-control" name="imagen" id="" aria-describedby="helpId" placeholder=""/>               
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-warning btn-sm" name="actualizar">
                        <i class="bi bi-floppy-fill"></i> Guardar Cambios
                    </button>
                    <a name="" id="" class="btn btn-info btn-sm" href="productos.php" role="button"> <i class="bi bi-arrow-left"></i> Volver</a>                
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer text-body-secondary"></div>
</div>

<?php
include '../templates/footer.php';
?>
