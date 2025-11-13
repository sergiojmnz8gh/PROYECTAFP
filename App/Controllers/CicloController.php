<?php

namespace App\Controllers;

use App\Repositories\RepoCiclo;

class CicloController
{
    public static function getAllCiclos() {
        header('Content-Type: application/json');
        $ciclos = RepoCiclo::findAll();
        echo json_encode(['success' => true, 'data' => $ciclos]);
    }

    // Opcional: Para dependencias
    public static function getCiclosByFamilia($familiaId) {
        header('Content-Type: application/json');
        $ciclos = RepoCiclo::findByFamilia($familiaId);
        echo json_encode(['success' => true, 'data' => $ciclos]);
    }
}