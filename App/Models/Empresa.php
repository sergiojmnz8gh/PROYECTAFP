<?php

namespace App\Models;

class Empresa {
    public $email;
    public $id;
    public $nombre;
    public $telefono;
    public $direccion;
    public $logo;
    public $user_id;
    public $activo;
    public $remember_token;

    public function __construct(
    ) {}
}
?>