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
                    o.id, o.titulo, o.descripcion, o.fecha_inicio, o.fecha_fin,
                    e.nombre AS empresa_nombre, 
                    e.id AS empresa_id,
                    c.nombre AS ciclo_nombre
                FROM ofertas o 
                JOIN empresas e ON o.empresa_id = e.id
                JOIN ciclos c ON o.ciclo_id = c.id";
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

    public static function findByEmpresa($empresaId) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE o.empresa_id = :empresa_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':empresa_id', $empresaId, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Oferta::class);
        return $stmt->fetchAll();
    }

    public static function findByCiclo($cicloId) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE o.ciclo_id = :ciclo_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':ciclo_id', $cicloId, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Oferta::class);
        return $stmt->fetchAll();
    }

    public static function findAll() {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery();
        $stmt = $conexion->query($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Oferta::class);
        return $stmt->fetchAll();
    }

    public static function findSizedList($pagination) {
        $page = $pagination['page'] ?? 1;
        $size = $pagination['size'] ?? 5;
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " LIMIT :size OFFSET :offset";
        $stmt = $conexion->prepare($sql);
        $stmt->bindvalue(':size', $size, PDO::PARAM_INT);
        $stmt->bindvalue(':offset', ($page - 1) * $size, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Oferta::class);
        return $stmt->fetchAll();
    }

    public static function create(Oferta $oferta) {
        error_log(print_r($oferta, true));        
        $conexion = self::getConexion();
        try {
            $sql = "INSERT INTO ofertas (titulo, descripcion, fecha_inicio, fecha_fin, empresa_id, ciclo_id) 
                    VALUES (:titulo, :descripcion, :fecha_inicio, :fecha_fin, :empresa_id, :ciclo_id)";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':titulo', $oferta->titulo);
            $stmt->bindParam(':descripcion', $oferta->descripcion);
            $stmt->bindParam(':fecha_inicio', $oferta->fecha_inicio);
            $stmt->bindParam(':fecha_fin', $oferta->fecha_fin);
            $stmt->bindParam(':empresa_id', $oferta->empresa_id, PDO::PARAM_INT);
            $stmt->bindParam(':ciclo_id', $oferta->ciclo_id, PDO::PARAM_INT);
            
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
                    empresa_id = :empresa_id
                    WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':titulo', $oferta->titulo);
            $stmt->bindParam(':descripcion', $oferta->descripcion);
            $stmt->bindParam(':fecha_inicio', $oferta->fecha_inicio);
            $stmt->bindParam(':fecha_fin', $oferta->fecha_fin);
            $stmt->bindParam(':empresa_id', $oferta->empresa_id, PDO::PARAM_INT);
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