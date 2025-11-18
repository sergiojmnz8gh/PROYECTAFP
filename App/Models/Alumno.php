<?php

namespace App\Models;

class Alumno {
    public $id;
    public $email;
    public $nombre;
    public $apellidos;
    public $telefono;
    public $direccion;
    public $foto;
    public $cv;
    public $ciclo_id;
    public $ciclo_nombre;
    public $user_id;
    public $remember_token;
    public $activo;

    public function __construct(
    ) {}
}
?>