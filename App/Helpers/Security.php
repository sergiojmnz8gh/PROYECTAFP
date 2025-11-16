<?php

namespace App\Helpers;

class Security {

    public static function hashPassword(string $password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword(string $password, string $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
}
