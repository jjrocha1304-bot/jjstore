<?php

// Permiso de conexión a la BD.
require '../config/db.php';

session_start();

$mensaje = "";

// El control de acceso.
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit();
}

// Procesamiento de creación de usuario.
if(isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $password_inicial = $_POST['password'];
    $rol = $_POST['rol'];

    $password_hasheada = password_hash($password_inicial, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO `usuarios`(`nombre`, `usuario`, `password`, `rol`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $usuario, $password_hasheada, $rol]);
        $mensaje = "Usuario creado exitosamente";
    } catch(PDOExceptio $e) {
        $mensaje = "Error al crear el usuario";

    }
}

// Consulta a la tabla de usuarios.
$stmt = $pdo->query("SELECT `id`, `nombre`, `usuario`, `rol` FROM `usuarios`");
$lista_usuarios = $stmt->fetchAll();

// Procesamiento de eliminar.
if(isset($_GET['eliminar'])) {
    $id_a_eliminar = $_GET['eliminar'];
    if($id_a_eliminar == $_SESSION['user_id']) {
        $mensaje = "No puedes eliminarte a ti mismo";
    } else {
        $stmt = $pdo->prepare("DELETE FROM `usuarios` WHERE id = ?");
        $stmt->execute([$id_a_eliminar]);
        header('Location: usuarios.php');
    }
}

include '../templates/header.php';
?>

<h2 class="text-center" style="margin-top:120px; padding-top:50px;">Gestión de Usuarios</h2>

<div class="card mb-5">
    <div class="card-header"><strong>Registro de Nuevo Usuario</strong></div>
    <div class="card-body">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <input type="text" class="form-control" name="nombre" id="" aria-describedby="helpId" placeholder="Digite el nombre completo del usuario" required autocomplete="off"/>
                </div>
                <div class="col-md-3 mb-3">
                    <input type="text" class="form-control" name="usuario" id="" aria-describedby="helpId" placeholder="Digite el nick de usuario" required autocomplete="off"/>
                </div>
                <div class="col-md-3 mb-3">
                    <input type="password" class="form-control" name="password" id="" aria-describedby="helpId" placeholder="Digite el password inicial de usuario" required autocomplete="off"/>
                </div>
                <div class="col-md-3 mb-3">               
                    <select class="form-select form-select-sm" name="rol" id="" required>
                        <option value="" selected disabled>Tipo de Rol</option>
                        <option value="admin">Administrador</option>
                        <option value="empleado">Empleado</option>                    
                    </select>
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm" name="registrar">
                        <i class="bi bi-floppy-fill"></i> Crear Usuario
                    </button>                
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer text-body-secondary"></div>
</div>

<hr>
<h3 class="text-center" mt-3>Usuarios Registrados en el Sistema</h3>
<?php if($mensaje): ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>
<div class="table-responsive">
    <table class="display table table-bordered table-hover align-middle" style="text-align: center;" id="myTable">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>     
            <?php foreach($lista_usuarios as $u): ?>       
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo $u['nombre']; ?></td>
                <td><?php echo $u['usuario']; ?></td>
                <td><strong><?php echo strtoupper($u['rol']); ?></strong></td>
                <td><a name="" id="" class="btn btn-danger btn-sm" href="?eliminar=<?php echo $u['id']; ?>" role="button"><i class="bi bi-trash-fill"></i> Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include '../templates/footer.php';
?>