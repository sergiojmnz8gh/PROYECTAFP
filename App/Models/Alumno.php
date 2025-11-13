<?php

namespace App\Models;

class Alumno {
    public $id;
    public $email;
    public $nombre;
    public $apellidos;
    public $direccion;
    public $telefono;
    public $foto;
    public $cv;
    public $user_id;
    public $remember_token;
    public $activo;

    public function __construct(
    ) {}
}
?>