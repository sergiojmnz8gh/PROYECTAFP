<?php

namespace App\Models;

class Oferta {
    public $id;
    public $empresa_id;
    public $titulo;
    public $descripcion;
    public $ciclos = [];
    public $fecha_inicio;
    public $fecha_fin;

    public function __construct(
    ) {}
}
?>