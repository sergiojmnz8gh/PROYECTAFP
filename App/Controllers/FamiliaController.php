<?php

namespace App\Controllers;

use App\Repositories\RepoFamilia;

class FamiliaController
{
    public static function getAllFamilias() {
        header('Content-Type: application/json');
        $familias = RepoFamilia::findAll();
        echo json_encode(['success' => true, 'data' => $familias]);
    }
}