<?php

namespace App\Helpers;

spl_autoload_register(function ($clase) {
    $baseProyectoDir = __DIR__ . '/../'; 
    $rutaRelativaClase = str_replace('\\', '/', $clase);
    $fichero = $baseProyectoDir . $rutaRelativaClase . '.php';

    if (file_exists($fichero)) {
        require_once $fichero;
    } else {
        throw new \Exception("Clase '" . $clase . "' no encontrada en la ruta: " . $fichero);
    }
});