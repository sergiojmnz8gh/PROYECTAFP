<?php

namespace App\Helpers;

class Sesion {
    public static function abrirSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function cerrarSesion() {
        self::abrirSesion();
        session_unset();
        session_destroy();
    }

    public static function leerSesion($clave) {
        self::abrirSesion();
        return $_SESSION[$clave] ?? null;
    }

    public static function escribirSesion($clave, $valor) {
        self::abrirSesion();
        $_SESSION[$clave] = $valor;
    }

    public static function existeClave($clave) {
        self::abrirSesion();
        return isset($_SESSION[$clave]);
    }
    
    public static function getUserId() {
        return self::leerSesion('user_id');
    }
    
    public static function getUserRol() {
        return self::leerSesion('user_rol');
    }
}