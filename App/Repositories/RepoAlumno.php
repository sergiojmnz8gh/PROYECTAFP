<?php

namespace App\Repositories;

use PDO;
use App\Models\Alumno;
use App\Repositories\DB;
use PDOException;

class RepoAlumno {
    
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
                    a.*, 
                    u.email, u.activo,
                    c.nombre AS ciclo_id
                FROM alumnos a 
                JOIN users u ON a.user_id = u.id
                JOIN ciclos c ON a.ciclo_id = c.id";
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

    public static function findByEmail($email) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE u.email = :email";
        $stmt = $conexion->prepare($sql);
        $stmt->bindvalue(':email', $email, PDO::PARAM_STR);
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

    public static function findSizedList($pagination) {
        $page = $pagination['page'] ?? 1;
        $size = $pagination['size'] ?? 5;
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " LIMIT :size OFFSET :offset";
        $stmt = $conexion->prepare($sql);
        $stmt->bindvalue(':size', $size, PDO::PARAM_INT);
        $stmt->bindvalue(':offset', ($page - 1) * $size, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Alumno::class);
        return $stmt->fetchAll();
    }

    public static function create($alumno, $hashedPassword) {
        $conexion = self::getConexion();
        $conexion->beginTransaction();
        try {
            $sqlUser = "INSERT INTO users (email, password, rol_id, activo) 
                        VALUES (:email, :password, :rol_id, :activo)";
            $stmtUser = $conexion->prepare($sqlUser);
            
            $stmtUser->bindvalue(':email', $alumno->email); 
            $stmtUser->bindvalue(':password', $hashedPassword);
            $stmtUser->bindvalue(':rol_id', 3, PDO::PARAM_INT); 
            $stmtUser->bindvalue(':activo', $alumno->activo, PDO::PARAM_BOOL); 
            
            $stmtUser->execute();
            $newUserId = $conexion->lastInsertId();

            $alumno->user_id = $newUserId; 

            $sqlAlumno = "INSERT INTO alumnos (nombre, apellidos, telefono, direccion, foto, cv, ciclo_id, user_id) 
                          VALUES (:nombre, :apellidos, :telefono, :direccion, :foto, :cv, :ciclo_id, :user_id)";
            $stmtAlumno = $conexion->prepare($sqlAlumno);
            
            $stmtAlumno->bindvalue(':nombre', $alumno->nombre);
            $stmtAlumno->bindvalue(':apellidos', $alumno->apellidos);
            $stmtAlumno->bindvalue(':telefono', $alumno->telefono);
            $stmtAlumno->bindvalue(':direccion', $alumno->direccion);
            $stmtAlumno->bindvalue(':foto', '/img/alumnos/'.$alumno->user_id.'.jpg');
            $stmtAlumno->bindvalue(':cv', '/resources/cvs/'.$alumno->user_id.'.pdf');
            $stmtAlumno->bindvalue(':ciclo_id', $alumno->ciclo_id, PDO::PARAM_INT);
            $stmtAlumno->bindvalue(':user_id', $alumno->user_id, PDO::PARAM_INT);
            
            $stmtAlumno->execute();

            $conexion->commit();
            return self::findById($conexion->lastInsertId());
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public static function update($alumno, $newHashedPassword = null) {
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
            error_log($e->getMessage());
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
            error_log($e->getMessage());
            return false;
        }
    }
}