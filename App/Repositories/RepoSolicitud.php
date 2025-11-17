<?php

namespace App\Repositories;

use PDO;
use App\Models\Solicitud;
use App\Repositories\DB;
use PDOException;

class RepoSolicitud {
    
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
                    s.*, 
                    o.titulo AS oferta_titulo,
                    a.nombre AS alumno_nombre,
                    a.apellidos AS alumno_apellidos
                FROM solicitudes s 
                JOIN ofertas o ON s.oferta_id = o.id
                JOIN alumnos a ON s.alumno_id = a.id";
    }

    public static function findById($id) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE s.id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Solicitud::class);
        return $stmt->fetch();
    }

    public static function findAll() {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery();
        $stmt = $conexion->query($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Solicitud::class);
        return $stmt->fetchAll();
    }

    public static function findSizedList($pagination) {
        $page = $pagination['page'] ?? 1;
        $size = $pagination['size'] ?? 4;
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " LIMIT :size OFFSET :offset";
        $stmt = $conexion->prepare($sql);
        $stmt->bindvalue(':size', $size, PDO::PARAM_INT);
        $stmt->bindvalue(':offset', ($page - 1) * $size, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Solicitud::class);
        return $stmt->fetchAll();
    }

    public static function create(Solicitud $solicitud) {
        $conexion = self::getConexion();
        try {
            $sql = "INSERT INTO solicitudes (oferta_id, user_id, fecha_solicitud) 
                    VALUES (:oferta_id, :user_id, :fecha_solicitud)";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':oferta_id', $solicitud->oferta_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $solicitud->alumno_id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_solicitud', $solicitud->fecha_solicitud);
            
            $stmt->execute();
            return self::findById($conexion->lastInsertId());
            
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public static function update(Solicitud $solicitud) {
        $conexion = self::getConexion();
        try {
            $sql = "UPDATE solicitudes SET 
                    oferta_id = :oferta_id, user_id = :user_id, fecha_solicitud = :fecha_solicitud,
                    WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':oferta_id', $solicitud->oferta_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $solicitud->alumno_id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_solicitud', $solicitud->fecha_solicitud);
            $stmt->bindParam(':id', $solicitud->id, PDO::PARAM_INT);
            
            $stmt->execute();
            return self::findById($solicitud->id);
            
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public static function delete($id) {
        $conexion = self::getConexion();
        try {
            $sql = "DELETE FROM solicitudes WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}