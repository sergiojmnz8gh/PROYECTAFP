<?php

namespace App\Repositories;

use PDO;
use App\Models\Empresa;
use App\Repositories\DB; 
use PDOException; 

class RepoEmpresa {
    
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
                    e.*, 
                    u.email, u.activo 
                FROM empresas e 
                JOIN users u ON e.user_id = u.id";
    }

    public static function findById($id) {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery() . " WHERE e.id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Empresa::class);
        return $stmt->fetch();
    }

    public static function findAll() {
        $conexion = self::getConexion();
        $sql = self::getBaseQuery();
        $stmt = $conexion->query($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Empresa::class);
        return $stmt->fetchAll();
    }

    public static function create(Empresa $empresa, string $hashedPassword) {
        $conexion = self::getConexion();
        $conexion->beginTransaction();
        try {
            $sqlUser = "INSERT INTO users (email, password, rol_id, activo) 
                        VALUES (:email, :password, :rol_id, :activo)";
            $stmtUser = $conexion->prepare($sqlUser);
            
            $stmtUser->bindParam(':email', $empresa->email); 
            $stmtUser->bindParam(':password', $hashedPassword);
            $stmtUser->bindParam(':rol_id', 2, PDO::PARAM_INT); 
            $stmtUser->bindParam(':activo', $empresa->activo, PDO::PARAM_BOOL); 
            
            $stmtUser->execute();
            $newUserId = $conexion->lastInsertId();

            $empresa->user_id = $newUserId; 

            $sqlEmpresa = "INSERT INTO empresas (nombre, telefono, direccion, logo, user_id) 
                          VALUES (:nombre, :telefono, :direccion, :logo, :user_id)";
            $stmtEmpresa = $conexion->prepare($sqlEmpresa);
            
            $stmtEmpresa->bindParam(':nombre', $empresa->nombre);
            $stmtEmpresa->bindParam(':telefono', $empresa->telefono);
            $stmtEmpresa->bindParam(':direccion', $empresa->direccion);
            $stmtEmpresa->bindParam(':logo', $empresa->logo);
            $stmtEmpresa->bindParam(':user_id', $empresa->user_id, PDO::PARAM_INT);
            
            $stmtEmpresa->execute();

            $conexion->commit();
            return self::findById($conexion->lastInsertId());
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            return false;
        }
    }

    public static function update(Empresa $empresa, ?string $newHashedPassword = null) {
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
            
            $stmtUser->bindParam(':email', $empresa->email); 
            $stmtUser->bindParam(':rol_id', 2, PDO::PARAM_INT); 
            $stmtUser->bindParam(':activo', $empresa->activo, PDO::PARAM_BOOL); 
            if ($newHashedPassword !== null) {
                $stmtUser->bindParam(':password', $newHashedPassword);
            }
            $stmtUser->bindParam(':user_id', $empresa->user_id, PDO::PARAM_INT);
            
            $stmtUser->execute();

            $sqlEmpresa = "UPDATE empresas SET 
                          nombre = :nombre, telefono = :telefono, direccion = :direccion, logo = :logo 
                          WHERE id = :id";
            $stmtEmpresa = $conexion->prepare($sqlEmpresa);
            
            $stmtEmpresa->bindParam(':nombre', $empresa->nombre);
            $stmtEmpresa->bindParam(':telefono', $empresa->telefono);
            $stmtEmpresa->bindParam(':direccion', $empresa->direccion);
            $stmtEmpresa->bindParam(':logo', $empresa->logo);
            $stmtEmpresa->bindParam(':id', $empresa->id, PDO::PARAM_INT);
            
            $stmtEmpresa->execute();

            $conexion->commit();
            return self::findById($empresa->id);
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            return false;
        }
    }

    public static function delete($id) {
        $conexion = self::getConexion();
        $conexion->beginTransaction();
        try {
            $stmt = $conexion->prepare("SELECT user_id FROM empresas WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $user_id = $stmt->fetchColumn();

            if (!$user_id) {
                 $conexion->rollBack();
                 return false;
            }

            $stmtOfertas = $conexion->prepare("SELECT id FROM ofertas WHERE empresa_id = :id");
            $stmtOfertas->execute([':id' => $id]);
            $ofertaIds = $stmtOfertas->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($ofertaIds)) {
                $placeholders = implode(',', array_fill(0, count($ofertaIds), '?'));

                $sql2 = "DELETE FROM solicitudes WHERE oferta_id IN ($placeholders)";
                $conexion->prepare($sql2)->execute($ofertaIds);
            }
            
            $sql3 = "DELETE FROM ofertas WHERE empresa_id = :id";
            $conexion->prepare($sql3)->execute([':id' => $id]);
            
            $sql4 = "DELETE FROM empresas WHERE id = :id";
            $conexion->prepare($sql4)->execute([':id' => $id]);
            
            $sql5 = "DELETE FROM users WHERE id = :user_id";
            $conexion->prepare($sql5)->execute([':user_id' => $user_id]);

            $conexion->commit();
            return true;
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            return false;
        }
    }
}