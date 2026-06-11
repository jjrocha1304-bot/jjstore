<?php
session_start();

// El control de acceso.
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit();
}

include '../templates/header.php';
?>

<h1 style="margin-top:100px; padding-top: 30px;">Interfaz del administrador</h1>
<h3 class="mt-3">Bienvenido al sistema, <strong><?php echo $_SESSION['nombre']; ?></strong>.</h3>
<?php
include '../templates/footer.php';
?>
