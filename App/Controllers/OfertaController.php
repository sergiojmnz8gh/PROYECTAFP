<?php

namespace App\Controllers;

use League\Plates\Engine;
use App\Helpers\Sesion; 
use App\Repositories\RepoOferta;
use App\Helpers\Adapter; 
use App\Helpers\Validator;

class OfertaController {

    protected $templates;
    protected $validator;

    public function __construct(Engine $templates) {
        $this->templates = $templates;
        $this->validator = new Validator();
    }

    public function list() {
        $buscarOferta = $_GET['buscarOferta'] ?? null;
        
        $ofertas = RepoOferta::findAll($buscarOferta);

        echo $this->templates->render('Admin/listadoOfertas', [
            'ofertas' => $ofertas,
            'error' => Sesion::leerSesion('error'),
            'success' => Sesion::leerSesion('success'),
        ]);
    }
}