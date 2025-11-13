<?php

namespace App\Repositories;

use PDO;
use App\Models\Rol;
use App\Repositories\DB;
use PDOException;

class RepoRol {
    
    private static $conexion;

    public function __construct() {
        if (self::$conexion === null) {
            self::$conexion = DB::getConexion();
        }
    }

    private static function getConexion() {
        if (self::$conexion === null) {
            self::$conexion = DB::getConexion();
        }
        return self::$conexion;
    }

    private static function getBaseQuery() {
        return "SELECT r.* FROM roles r";
    }

    public static function findById($id) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE r.id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Rol::class);
        return $stmt->fetch();
    }

    public static function findAll() {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery();
        $stmt = $conexion->query($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Rol::class);
        return $stmt->fetchAll();
    }

    public static function create(Rol $rol) {
        $conexion = self::getConexion();
        try {
            $sql = "INSERT INTO roles (nombre) VALUES (:nombre)";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':nombre', $rol->nombre);
            
            $stmt->execute();
            return self::findById($conexion->lastInsertId());
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update(Rol $rol) {
        $conexion = self::getConexion();
        try {
            $sql = "UPDATE roles SET nombre = :nombre WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':nombre', $rol->nombre);
            $stmt->bindParam(':id', $rol->id, PDO::PARAM_INT);
            
            $stmt->execute();
            return self::findById($rol->id);
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete($id) {
        $conexion = self::getConexion();
        try {
            $sql = "DELETE FROM roles WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            return false;
        }
    }
}