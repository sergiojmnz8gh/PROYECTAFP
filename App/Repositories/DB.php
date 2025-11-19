<?php

namespace App\Repositories;

use PDO;
use PDOException;

class DB {
    
    private static $conexion = null;

    private function __construct() {}

    public static function getConexion() {
        if (self::$conexion === null) {
            try {
                $host = 'mysql';
                $dbname = 'proyectafp';
                $user = 'root';
                $pass = 'root';

                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

                $opciones = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$conexion = new PDO($dsn, $user, $pass, $opciones);
            } catch (PDOException $e) {
                die("Error de conexión a la Base de Datos: " . $e->getMessage());
            }
        }
        
        return self::$conexion;
    }
}
?>