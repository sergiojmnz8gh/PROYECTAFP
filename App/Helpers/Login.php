<?php

namespace App\Helpers;

use App\DTO\UserDTO;
use App\Helpers\Sesion;

class Login {

    public static function login($user) {
        Sesion::escribirSesion('user_id', $user->id);
        Sesion::escribirSesion('user_email', $user->email);
        Sesion::escribirSesion('user_rol', $user->rol_id);
    }

    public static function logout() {
        Sesion::cerrarSesion();
    }

    public static function estaLogeado(): bool {
        return Sesion::existeClave('user_id');
    }

    public static function getLoggedInUserId(): ?int {
        return Sesion::leerSesion('user_id');
    }

    public static function getLoggedInUserEmail(): ?string {
        return Sesion::leerSesion('user_email');
    }

    public static function getLoggedInUserRol(): ?int {
        return Sesion::leerSesion('user_rol');
    }
}