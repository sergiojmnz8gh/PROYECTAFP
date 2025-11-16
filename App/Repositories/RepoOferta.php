<?php

namespace App\Repositories;

use PDO;
use App\Models\Oferta;
use App\Repositories\DB;
use PDOException;

class RepoOferta {
    
    private static $conexion;

    public function __construct() {
    }

    private static function getConexion() {
        if (self::$conexion === null) {
            self::$conexion = DB::getConexion();
        }
        return self::$conexion;
    }

    private static function getBaseQuery() {
        return "SELECT 
                    o.*, 
                    e.nombre AS empresa_nombre, 
                    c.nombre AS ciclo_nombre,
                    u.email AS empresa_email
                FROM ofertas o 
                JOIN empresas e ON o.empresa_id = e.id
                JOIN users u ON e.user_id = u.id
                LEFT JOIN ciclos c ON o.ciclo_id = c.id";
    }

    public static function findById($id) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE o.id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Oferta::class);
        return $stmt->fetch();
    }

    public static function findAll() {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery();
        $stmt = $conexion->query($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Oferta::class);
        return $stmt->fetchAll();
    }

    public static function create(Oferta $oferta) {
        $conexion = self::getConexion();
        try {
            $sql = "INSERT INTO ofertas (titulo, descripcion, fecha_inicio fecha_fin, empresa_id, ciclo_id) 
                    VALUES (:titulo, :descripcion, :fecha_inicio, :fecha_fin, :empresa_id, :ciclo_id)";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':titulo', $oferta->titulo);
            $stmt->bindParam(':descripcion', $oferta->descripcion);
            $stmt->bindParam(':fecha_inicio', $oferta->fecha_inicio);
            $stmt->bindParam(':fecha_fin', $oferta->fecha_fin);
            $stmt->bindParam(':empresa_id', $oferta->empresa_id, PDO::PARAM_INT);
            $stmt->bindParam(':ciclo_id', $oferta->ciclos, PDO::PARAM_INT);
            
            $stmt->execute();
            return self::findById($conexion->lastInsertId());
            
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public static function update(Oferta $oferta) {
        $conexion = self::getConexion();
        try {
            $sql = "UPDATE ofertas SET 
                    titulo = :titulo, descripcion = :descripcion, 
                    fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, 
                    empresa_id = :empresa_id, ciclo_id = :ciclo_id 
                    WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':titulo', $oferta->titulo);
            $stmt->bindParam(':descripcion', $oferta->descripcion);
            $stmt->bindParam(':fecha_inicio', $oferta->fecha_inicio);
            $stmt->bindParam(':fecha_fin', $oferta->fecha_fin);
            $stmt->bindParam(':empresa_id', $oferta->empresa_id, PDO::PARAM_INT);
            $stmt->bindParam(':ciclo_id', $oferta->ciclos, PDO::PARAM_INT);
            $stmt->bindParam(':id', $oferta->id, PDO::PARAM_INT);
            
            $stmt->execute();
            return self::findById($oferta->id);
            
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public static function delete($id) {
        $conexion = self::getConexion();
        $conexion->beginTransaction();
        try {
            $stmtSolicitudes = $conexion->prepare("DELETE FROM solicitudes WHERE oferta_id = :id");
            $stmtSolicitudes->execute([':id' => $id]);
            
            $sql = "DELETE FROM ofertas WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            $stmt->execute();
            $conexion->commit();
            return true;
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
}