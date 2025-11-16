<?php

namespace App\Repositories;

use PDO;
use App\Models\User;
use App\Repositories\DB; 

class RepoUser { 
    private static $conexion = null;

    public function __construct() {
    }

    private static function getConexion() {
        if (self::$conexion === null) {
            self::$conexion = DB::getConexion();
        }
        return self::$conexion;
    }

    public static function findUser($email) {
        $conexion = self::getConexion();
        $sql = "SELECT id, email, password, rol_id, activo FROM users WHERE email = :email";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function existeUser($email) {
        $conexion = self::getConexion();
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = :email";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ); 
        return $result->count > 0;
    }

    public static function findById($id) {
        $conexion = self::getConexion();
        $sql = "SELECT id, email, password, rol_id, activo FROM users WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        return $stmt->fetch() ?: null;
    }
}