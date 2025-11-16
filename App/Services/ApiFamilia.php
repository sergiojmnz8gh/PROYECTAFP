<?php

namespace App\Services;

use App\Repositories\RepoFamilia;

class ApiFamilia {
    public static function getAllFamilias() {
        header('Content-Type: application/json');
        $familias = RepoFamilia::findAll();
        echo json_encode(['success' => true, 'data' => $familias]);
    }
}