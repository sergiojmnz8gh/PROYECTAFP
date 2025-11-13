<?php

namespace App\Repositories;

use PDO;
use App\Models\Alumno;
use App\Repositories\DB;
use PDOException;

class RepoAlumno {
    
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
        return "SELECT 
                    a.*, 
                    u.email, u.activo
                FROM alumnos a 
                JOIN users u ON a.user_id = u.id";
    }

    public static function findById($id) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE a.id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Alumno::class);
        return $stmt->fetch();
    }

    public static function findAll() {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery();
        $stmt = $conexion->query($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Alumno::class);
        return $stmt->fetchAll();
    }

    public static function create(Alumno $alumno, string $hashedPassword) {
        $conexion = self::getConexion();
        $conexion->beginTransaction();
        try {
            $sqlUser = "INSERT INTO users (email, password, rol_id, activo) 
                        VALUES (:email, :password, :rol_id, :activo)";
            $stmtUser = $conexion->prepare($sqlUser);
            
            $stmtUser->bindvalue(':email', $alumno->email); 
            $stmtUser->bindvalue(':password', $hashedPassword);
            $stmtUser->bindvalue(':rol_id', 2, PDO::PARAM_INT); 
            $stmtUser->bindvalue(':activo', $alumno->activo, PDO::PARAM_BOOL); 
            
            $stmtUser->execute();
            $newUserId = $conexion->lastInsertId();

            $alumno->user_id = $newUserId; 

            $sqlAlumno = "INSERT INTO alumnos (nombre, apellidos, telefono, foto, user_id) 
                          VALUES (:nombre, :apellidos, :telefono, :foto, :user_id)";
            $stmtAlumno = $conexion->prepare($sqlAlumno);
            
            $stmtAlumno->bindvalue(':nombre', $alumno->nombre);
            $stmtAlumno->bindvalue(':apellidos', $alumno->apellidos);
            $stmtAlumno->bindvalue(':telefono', $alumno->telefono);
            $stmtAlumno->bindvalue(':foto', $alumno->foto);
            $stmtAlumno->bindvalue(':user_id', $alumno->user_id, PDO::PARAM_INT);
            
            $stmtAlumno->execute();

            $conexion->commit();
            return self::findById($conexion->lastInsertId());
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            return false;
        }
    }

    public static function update(Alumno $alumno, ?string $newHashedPassword = null) {
        $conexion = self::getConexion();
        $conexion->beginTransaction();
        try {
            $sqlUser = "UPDATE users SET 
                        email = :email, rol_id = :rol_id, activo = :activo";
            if ($newHashedPassword !== null) {
                $sqlUser .= ", password = :password";
            }
            $sqlUser .= " WHERE id = :user_id";
            $stmtUser = $conexion->prepare($sqlUser);
            
            $stmtUser->bindvalue(':email', $alumno->email); 
            $stmtUser->bindvalue(':rol_id', 3, PDO::PARAM_INT); 
            $stmtUser->bindvalue(':activo', $alumno->activo, PDO::PARAM_BOOL); 
            if ($newHashedPassword !== null) {
                $stmtUser->bindvalue(':password', $newHashedPassword);
            }
            $stmtUser->bindvalue(':user_id', $alumno->user_id, PDO::PARAM_INT);
            
            $stmtUser->execute();

            $sqlAlumno = "UPDATE alumnos SET 
                          nombre = :nombre, apellidos = :apellidos, telefono = :telefono, foto = :foto 
                          WHERE id = :id";
            $stmtAlumno = $conexion->prepare($sqlAlumno);
            
            $stmtAlumno->bindvalue(':nombre', $alumno->nombre);
            $stmtAlumno->bindvalue(':apellidos', $alumno->apellidos);
            $stmtAlumno->bindvalue(':telefono', $alumno->telefono);
            $stmtAlumno->bindvalue(':foto', $alumno->foto);
            $stmtAlumno->bindvalue(':id', $alumno->id, PDO::PARAM_INT);
            
            $stmtAlumno->execute();

            $conexion->commit();
            return self::findById($alumno->id);
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            return false;
        }
    }

    public static function delete($id) {
        $conexion = self::getConexion();
        $conexion->beginTransaction();
        try {
            $stmt = $conexion->prepare("SELECT user_id FROM alumnos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $user_id = $stmt->fetchColumn();

            if (!$user_id) {
                $conexion->rollBack();
                return false;
            }

            $stmtSolicitudes = $conexion->prepare("DELETE FROM solicitudes WHERE alumno_user_id = :user_id");
            $stmtSolicitudes->execute([':user_id' => $user_id]);
            
            $sql1 = "DELETE FROM alumnos WHERE id = :id";
            $conexion->prepare($sql1)->execute([':id' => $id]);
            
            $sql2 = "DELETE FROM users WHERE id = :user_id";
            $conexion->prepare($sql2)->execute([':user_id' => $user_id]);

            $conexion->commit();
            return true;
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            return false;
        }
    }
}