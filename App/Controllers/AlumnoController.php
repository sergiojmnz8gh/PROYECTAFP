<?php
namespace App\Controllers;

use League\Plates\Engine;
use App\Repositories\RepoAlumno;
use App\Helpers\Sesion;

class AlumnoController {

    protected $templates;
    protected $repoAlumno;

    public function __construct(Engine $templates) {
        $this->templates = $templates;
        $this->repoAlumno = new RepoAlumno(); 
    }

    public function list() {
        $alumnos = $this->repoAlumno->findAll();
        
        $mensajeExito = Sesion::leerSesion('success_message');
        $mensajeError = Sesion::leerSesion('error_message');

        echo $this->templates->render('Admin/listadoAlumnos', [
            'alumnos' => $alumnos,
            'success_message' => $mensajeExito,
            'error_message' => $mensajeError,
        ]);
    }
}