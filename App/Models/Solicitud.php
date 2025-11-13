<?php

namespace App\Models;

class Solicitud {
    public $id; 
    public $fecha_solicitud;
    public $alumno_id;     
    public $oferta_id;     
    public $cv_visto;

    public function __construct(
    ) {}
}
?>