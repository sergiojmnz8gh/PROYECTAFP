<?php

namespace App\Repositories;

use PDO;
use App\Models\Familia;
use App\Repositories\DB;
use PDOException;

class RepoFamilia {
    
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
        return "SELECT f.* FROM familias f";
    }

    public static function findById($id) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE f.id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Familia::class);
        return $stmt->fetch();
    }

    public static function findAll() {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery();
        $stmt = $conexion->query($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Familia::class);
        return $stmt->fetchAll();
    }

    public static function create(Familia $familia) {
        $conexion = self::getConexion();
        try {
            $sql = "INSERT INTO familias (nombre) VALUES (:nombre)";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':nombre', $familia->nombre);
            
            $stmt->execute();
            return self::findById($conexion->lastInsertId());
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update(Familia $familia) {
        $conexion = self::getConexion();
        try {
            $sql = "UPDATE familias SET nombre = :nombre WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':nombre', $familia->nombre);
            $stmt->bindParam(':id', $familia->id, PDO::PARAM_INT);
            
            $stmt->execute();
            return self::findById($familia->id);
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete($id) {
        $conexion = self::getConexion();
        $conexion->beginTransaction();
        try {
            $stmtCiclos = $conexion->prepare("SELECT id FROM ciclos WHERE familia_id = :id");
            $stmtCiclos->execute([':id' => $id]);
            $cicloIds = $stmtCiclos->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($cicloIds)) {
                $placeholders = implode(',', array_fill(0, count($cicloIds), '?'));
                $sqlRequisitos = "DELETE FROM requisitos_ciclos WHERE ciclo_id IN ($placeholders)";
                $conexion->prepare($sqlRequisitos)->execute($cicloIds);
                
                $sqlOfertas = "DELETE FROM ofertas WHERE ciclo_id IN ($placeholders)";
                $conexion->prepare($sqlOfertas)->execute($cicloIds);

                $sqlCiclos = "DELETE FROM ciclos WHERE familia_id = :id";
                $conexion->prepare($sqlCiclos)->execute([':id' => $id]);
            }
            
            $sql = "DELETE FROM familias WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            $stmt->execute();
            $conexion->commit();
            return true;
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            return false;
        }
    }
}