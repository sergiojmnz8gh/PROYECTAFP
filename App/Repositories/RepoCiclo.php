<?php

namespace App\Repositories;

use PDO;
use App\Models\Ciclo;
use App\Repositories\DB;
use PDOException;

class RepoCiclo {
    
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
        return "SELECT c.* FROM ciclos c JOIN familias f ON c.familia_id = f.id";
    }

    public static function findById($id) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE c.id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Ciclo::class);
        return $stmt->fetch();
    }

    public static function findAll() {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery();
        $stmt = $conexion->query($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Ciclo::class);
        return $stmt->fetchAll();
    }

    public static function findByFamilia($familiaId) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE c.familia_id = :familia_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':familia_id', $familiaId, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Ciclo::class);
        return $stmt->fetchAll();
    }

    public static function create(Ciclo $ciclo) {
        $conexion = self::getConexion();
        try {
            $sql = "INSERT INTO ciclos (nombre, familia_id) VALUES (:nombre, :familia_id)";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':nombre', $ciclo->nombre);
            $stmt->bindParam(':familia_id', $ciclo->familia_id, PDO::PARAM_INT);
            
            $stmt->execute();
            return self::findById($conexion->lastInsertId());
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update(Ciclo $ciclo) {
        $conexion = self::getConexion();
        try {
            $sql = "UPDATE ciclos SET nombre = :nombre, familia_id = :familia_id WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':nombre', $ciclo->nombre);
            $stmt->bindParam(':familia_id', $ciclo->familia_id, PDO::PARAM_INT);
            $stmt->bindParam(':id', $ciclo->id, PDO::PARAM_INT);
            
            $stmt->execute();
            return self::findById($ciclo->id);
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete($id) {
        $conexion = self::getConexion();
        $conexion->beginTransaction();
        try {
            $stmtRequisitos = $conexion->prepare("DELETE FROM requisitos_ciclos WHERE ciclo_id = :id");
            $stmtRequisitos->execute([':id' => $id]);

            $sql = "DELETE FROM ciclos WHERE id = :id";
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