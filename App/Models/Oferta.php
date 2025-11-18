<?php

namespace App\Models;

class Oferta {
    public $id;
    public $empresa_nombre;
    public $empresa_id;
    public $titulo;
    public $descripcion;
    public $ciclo_nombre;
    public $ciclo_id;
    public $fecha_inicio;
    public $fecha_fin;

    public function __construct(
    ) {}
}
?>