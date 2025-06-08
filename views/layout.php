<?php 

    if(!isset($_SESSION)) {
           session_start();
    }
    $auth = $_SESSION['login'] ?? false;

    if(!isset($inicio)) {
        $inicio = false;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Bienes raices</title>
    <link rel="stylesheet" href="../build/css/app.css">
</head>
<body>
    <header class="header <?php echo $inicio ? 'inicio' : ''; ?>">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="/">
                    <img src="/build/img/logo.svg" alt="Logotipo de bienes raices">
                </a>

                <div class="mobile-menu">
                    <img src="/build/img/barras.svg" alt="icono menu responsive">
                </div>
                <div class="derecha">
                    <img class="dark-mode-boton" src="/build/img/dark-mode.svg"> 
                    <nav class="navegacion">
                        <a href="/nosotros">Nosotros</a>
                        <a href="/propiedades">Anuncios</a>
                        <a href="/blog">Blog</a>
                        <a href="/contacto">Contacto</a>
                       
                         <?php if(!$auth): ?>
                             <a href="/login">Iniciar Sesión</a>
                        <?php endif; ?>

                        <?php if($auth): ?>
                            <a href="/logout">Cerrar Sesión</a>
                            <a href="/admin">Administrar</a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div> <!--.barra-->
            <?php 
                if($inicio) {
                    echo "<h1>Venta de casas y Departamentos</h1>";
                }
            ?>
       
        </div>
    </header>

    <?php echo $contenido; ?>

    <footer class="footer seccion">
        <div class="contenedor contenedor-footer">
            <nav class="navegacion">
                <a href="/nosotros">Nosotros</a>
                <a href="/propiedades">Anuncios</a>
                <a href="/blog">Blog</a>
                <a href="/contacto">Contacto</a>
            </nav>
        </div>

        <p class="copyrigth">Todos los derechos reservados Christopher Revelo <?php echo date('Y'); ?> &copy;</p>	
    </footer>

    <script src="../build/js/bundle.min.js"></script>
</body>
</html>
