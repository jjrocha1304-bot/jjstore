<?php
// Permiso de conexión a la BD.
require 'config/db.php';

// Configurar el inicio de sesión.
session_start();

// Restringitr el acceso.
if(isset($_SESSION['rol'])){
    if($_SESSION['rol'] == 'admin'){
        header('Location: admin/index.php');
    } else {
        header('Location: vendedor/index.php');
    }
}

// Confirmación de que la información viene del metodo POST.
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Asignación de valores de los inputs.
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Buscar el usuario en la BD.
    $stmt = $pdo->prepare("SELECT * FROM `usuarios` WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    // Validación de la contraseña.
    if($user && password_verify($password, $user['password'])){
        // Creación de las variables de sesón.
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];

        // Redireccionar según el rol
        if($user['rol'] == 'admin'){
            header('Location: admin/index.php');
        } else {
            header('Location: vendedor/index.php');
        }
        exit();
    } else {
        $error = "Usuario o contraseña incorrecta";
    }
}

?>

<!doctype html>
<html lang="es" data-bs-theme="light">
    <head>
        <title>Login - JJ Store</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" type="image/png" href="imagenes/favicon.png" />

        <!-- Bootstrap CSS v5.3.8 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
        <!-- Estilos personalizados CSS -->
            <link rel="stylesheet" href="css/styles.css">
    </head>
    <body class="contenedor-ppal">
        <header>
            <!-- place navbar here -->
        </header>
        <div>
            <main class="container">
                <div class="text-center mt-5 mb-5">
                    <a class="navbar-brand d-flex flex-column align-items-center mt-2 mb-3" href="index.php">
                        <img src="imagenes/logo_JJ_Store.png" alt="Logo" width="80" height="80" class="border border-dark rounded-circle">
                        <span class="ms-2 fs-5 fw-bold">Store</span>
                    </a>
                    <h3 class="text-center p-2">Bienvenido</h3>                    
                </div>            
                <div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <form action="" method="post">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Usuario</label>
                                            <input type="text" class="form-control" name="usuario" id="" aria-describedby="helpId" placeholder="Digite su nombre de usuario" 
                                            required autocomplete="off"/>                    
                                        </div>
                                        <div class="mb-3">
                                            <label for="" class="form-label">Contraseña</label>
                                            <input type="password" class="form-control" name="password" id="" aria-describedby="helpId" placeholder="Digite su contraseña" 
                                            required autocomplete="off" />
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Iniciar Sesión
                                            </button>
                                        </div>    
                                    </form>
                                </div>
                                <div class="card-footer text-body-secondary"></div>
                            </div>                    
                        </div>
                    </div>
                </div>                        
            </main>

        </div>
        
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Bundle (includes Popper) -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
