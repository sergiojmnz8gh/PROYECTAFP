<?php

namespace App\Models;

class User {
    public $id;
    public $email;
    public $password;
    public $rol_id;
    public $remember_token;
    public $activo;
    
    public function __construct(
    ) {}
}
?>