<?php
    ob_start();
    require '../vendor/autoload.php';
    require '../Helpers/MiAutoloader.php';
    use App\Controllers\Router;
    $router = new Router();
    $router->run();
?>