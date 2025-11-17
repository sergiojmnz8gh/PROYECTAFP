<?php

namespace App\Models;

class Solicitud {
    public $id; 
    public $oferta_titulo;
    public $fecha_solicitud;
    public $alumno_id;     
    public $alumno_nombre;
    public $alumno_apellidos;
    public $oferta_id;     
    public $cv_visto;

    public function __construct(
    ) {}
}
?>