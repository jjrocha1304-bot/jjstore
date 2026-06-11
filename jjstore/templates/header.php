<!doctype html>
<html lang="es" data-bs-theme="light">
    <head>
        <title>JJ Store - Almacén de Ropa</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" type="image/png" href="../imagenes/favicon.png"/>

        <!-- Bootstrap CSS v5.3.8 -->
            <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"/>
        
        <!-- Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
        
        <!-- Data tables -->
            <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.8/datatables.min.css" rel="stylesheet" integrity="sha384-nD9P196GmYuiIASpxI7+7/0LqD6BBA74CfgIOSQUo7brmKKeph8lSEMm2sGgSAvK" crossorigin="anonymous">
 
            <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.8/datatables.min.js" integrity="sha384-pszF7QvU8ChuuhQBJGIvzVEcojfpYqQOVG2PaXES4M4Mcn+1CqfQ4r1b/mpdtn/6" crossorigin="anonymous"></script>

         <!-- Estilos personalizados CSS -->
            <link rel="stylesheet" href="../css/styles.css">
    </head>
    <body>
        <header>
            <!-- place navbar here -->
             <nav class="navbar navbar-expand navbar-light bg-light fixed-top menu-tienda" id="menu-tienda">
                <a class="navbar-brand ms-5 d-flex flex-column align-items-center" href="index.php">
                    <img src="../imagenes/logo_JJ_Store.png" alt="Logo" width="60" height="60" class="border border-dark rounded-circle">
                    <span class="ms-2 fs-5 fw-bold">Store</span>
                </a>                
                <ul class="nav navbar-nav ms-auto me-5">
                    <?php
                    if($_SESSION['rol'] == 'admin'):
                    ?>
                    <li class="nav-item enlace-menu">
                        <a class="nav-link"  href="../admin/index.php">Inicio</a>
                    </li>
                    <li class="nav-item enlace-menu">
                        <a class="nav-link" href="../admin/productos.php">Gestionar inventario</a>
                    </li>
                    <li class="nav-item enlace-menu">
                        <a class="nav-link" href="../admin/usuarios.php">Gestionar usuarios</a>
                    </li>
                    <?php
                    endif;
                    ?>

                    <?php
                    if($_SESSION['rol'] == 'empleado'):
                    ?>
                    <li class="nav-item enlace-menu">
                        <a class="nav-link" href="../vendedor/index.php">Inicio</a>
                    </li>
                    <li class="nav-item enlace-menu">
                        <a class="nav-link" href="../vendedor/catalogo.php">Ver catálogo</a>
                    </li>
                    <?php
                    endif;
                    ?>
                    
                    <li class="nav-item enlace-menu">
                        <a class="nav-link" href="../logout.php">Cerrar sesión</a>
                    </li>
                </ul>
             </nav>             
        </header>
        <main class= "container">

       
