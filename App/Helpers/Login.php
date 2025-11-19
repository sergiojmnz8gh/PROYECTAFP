<?php

namespace App\Helpers;

use App\Helpers\Sesion;

class Login {

    public static function login($user) {
        Sesion::escribirSesion('user_id', $user['id']);
        Sesion::escribirSesion('user_email', $user['email']);
        Sesion::escribirSesion('user_rol', $user['rol_id']);
    }

    public static function logout() {
        Sesion::cerrarSesion();
    }

    public static function estaLogeado() {
        return Sesion::existeClave('user_id');
    }

    public static function getLoggedInUserId() {
        return Sesion::leerSesion('user_id');
    }

    public static function getLoggedInUserEmail() {
        return Sesion::leerSesion('user_email');
    }

    public static function getLoggedInUserRol() {
        return Sesion::leerSesion('user_rol');
    }
}