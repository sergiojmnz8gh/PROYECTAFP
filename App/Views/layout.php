<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($title) ?></title>
    <link rel="icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">

    <?= $this->section('css') ?>
    <?= $this->section('js') ?>
</head>

<body>
    <header class="main-header">
        <a href="/"><img src="img/logo.png" alt="Logo ProyectaFP"></a>
        <nav>
            <ul>
                <?php
                if (isset($_SESSION['rol'])) {
                    if ($_SESSION['rol'] == 'admin') {
                        echo '<li><a href="index.php?">Perfil</a></li>';
                    }
                    if ($_SESSION['rol'] == 'alumno') {
                        echo '<li><a href="index.php?pagina=">Buscar ofertas</a></li>
                            <li><a href="index.php?pagina=">Mis solicitudes</a></li>
                            <li><a href="index.php?pagina="><button class="btn1">Perfil</button></a></li>';
                    }
                    if ($_SESSION['rol'] == 'empresa') {
                        echo '<li><a href="index.php?pagina=">Nueva oferta</a></li>
                            <li><a href="index.php?pagina=">Mis ofertas</a></li>
                            <li><a href="index.php?pagina="><button class="btn1">Perfil</button></a></li>';
                    }
                } else {
                    echo '<li><a href="index.php#alumno">Soy Alumno</a></li>
                        <li><a href="index.php?#empresa">Soy Empresa</a></li>
                        <li><a href="index.php?pagina=login"><button class="btn1">Iniciar Sesión</button></a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="admin-page">
        <?php
            if ((isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin')) { 
                ?>
        <aside class="sidebar">
                    <div class="header-paneladmin">
                        <img class="icon" src="img/admin.png">
                        <h2>Administrar</h2>
                    </div>
                    <ul>
                        <li><a href="index.php?admin=dashboard">Dashboard</a></li>
                        <li><a href="index.php?admin=alumnos">Alumnos</a></li>
                        <li><a href="index.php?admin=empresas">Empresas</a></li>
                        <li><a href="index.php?admin=ofertas">Ofertas</a></li>
                        <li><a href="index.php?admin=solicitudes">Solicitudes</a></li>
                        <li><a href="index.php?admin=ciclos">Ciclos</a></li>
                        <li><a href="index.php?admin=familias">Familias</a></li>
                        <li><a href="index.php?admin=roles">Roles</a></li>
                    </ul>
                <?php
            }
            ?>
        </aside>
        <?= $this->section('contenidoPagina') ?>
        </div>
    </main>

    <footer>
        <div class="footer-main">
            <div>
                <p class="titulillo">Enlaces de interés</p>
                <nav>
                    <ul>
                        <li><a href="">Mapa del sitio</a></li>
                        <li><a href="">Política de privacidad</a></li>
                        <li><a href="">Política de cookies</a></li>
                    </ul>
                </nav>
            </div>
            <div>
                <p class="titulillo">Síguenos!</p>
                <div>
                    <a href=""><img class="icono" src="img/linkedin.png" alt="LinkedIn"></a>
                    <a href=""><img class="icono" src="img/instagram.png" alt="Instagram"></a>
                    <a href=""><img class="icono" src="img/x.png" alt="X (Twitter)"></a>
                </div>
                <img src="img/logo.png" alt="Logo">
            </div>
        </div>
        <p class="titulillo">© 2025. ProyectaFP. Todos los derechos reservados.</p>
    </footer>
</body>

</html>