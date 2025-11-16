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
                    u.email AS alumno_email
                FROM solicitudes s 
                JOIN ofertas o ON s.oferta_id = o.id
                JOIN users u ON s.user_id = u.id";
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