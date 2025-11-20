<?php

namespace App\Controllers;

use League\Plates\Engine;
use App\Helpers\Sesion; 
use App\Helpers\Login;
use App\Helpers\Validator;
use App\Models\Oferta;
use App\Repositories\RepoOferta;
use App\Repositories\RepoSolicitud;
use App\Repositories\RepoEmpresa;
use App\Repositories\RepoAlumno;
use App\Helpers\Adapter;
use Exception;

class OfertaController {

    protected $templates;
    protected $validator;

    public function __construct(Engine $templates) {
        $this->templates = $templates;
        $this->validator = new Validator();
    }

    public function list() {
        if (Login::getLoggedInUserRol() == '1') {
            $ofertas = RepoOferta::findAll();

            echo $this->templates->render('Admin/listadoOfertas', [
                'ofertas' => $ofertas,
                'error' => Sesion::leerSesion('error'),
                'success' => Sesion::leerSesion('success'),
            ]);
        } else {
            header('Location: /index.php');
        }
    }

    public function listMisOfertas() {
        if (Login::getLoggedInUserRol() == '2') {
            $empresa = RepoEmpresa::findByUserId(Login::getLoggedInUserId());
            $ofertas = RepoOferta::findByEmpresa($empresa->id);

            echo $this->templates->render('Empresa/listadoMisOfertas', [
                'ofertas' => $ofertas,
                'error' => Sesion::leerSesion('error'),
                'success' => Sesion::leerSesion('success'),
            ]);
        } else {
            header('Location: /index.php');
        }
    }

    public function listOfertasDisponibles() {
        try{
            if (Login::getLoggedInUserRol() == '3') {
                $alumno = RepoAlumno::findByUserId(Login::getLoggedInUserId());
                $excluidas = RepoSolicitud::findOfertasIdByAlumnoId($alumno->id);
                $ofertas = RepoOferta::findDisponiblesByCiclo($alumno->ciclo_id, $excluidas);

            echo $this->templates->render('Alumno/listadoOfertas', [
                'ofertas' => $ofertas,
                'error' => Sesion::leerSesion('error'),
                'success' => Sesion::leerSesion('success'),
            ]);
            }
        } catch (Exception) {
            echo $this->templates->render('Alumno/listadoOfertas', [
                'ofertas' => null,
                'error' => Sesion::leerSesion('error'),
                'success' => Sesion::leerSesion('success'),
            ]);
        }
    }

    public function create() {
        echo $this->templates->render('Empresa/nuevaOferta');
    }

    public function save_create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?page=nuevaoferta');
        }

        $this->validator->Requerido('titulo');
        $this->validator->CadenaRango('titulo', 100, 3);
        
        $this->validator->Requerido('desc');
        $this->validator->CadenaRango('desc', 1000, 3);
        
        $this->validator->Requerido('ciclo_id');
        
        $this->validator->Requerido('fecha_inicio');
        $this->validator->Requerido('fecha_fin');

        if (!$this->validator->ValidacionPasada()) {
            Sesion::escribirSesion('error', 'Por favor, corrige los errores del formulario.');
            Sesion::escribirSesion('old_input', $_POST);
            
            header("Location: /index.php?page=nuevaoferta");
        }

        $titulo = $_POST['titulo'];
        $desc = $_POST['desc'];
        $ciclo_id = $_POST['ciclo_id'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];

        try {
            $oferta = new Oferta();
            $oferta->titulo = $titulo;
            $oferta->descripcion = $desc;
            $oferta->empresa_id = RepoEmpresa::findByUserId(Login::getLoggedInUserId())->id;
            $oferta->ciclo_id = $ciclo_id;
            $oferta->fecha_inicio = $fecha_inicio;
            $oferta->fecha_fin = $fecha_fin;

            RepoOferta::create($oferta);
            Sesion::escribirSesion('success', 'Oferta creada correctamente.');
            header('Location: /index.php?page=misofertas');
        } catch (Exception $e) {
            error_log("Error de creación de oferta: " . $e->getMessage());
            Sesion::escribirSesion('error', 'Ocurrió un error inesperado durante la creación.');
            header('Location: /index.php?page=nuevaoferta');
        }
    }

    public function edit() {
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            Sesion::escribirSesion('error', 'ID de oferta no proporcionado.');
            header('Location: /index.php?admin=ofertas');
        }

        $id = (int)$_POST['id'];

        $oferta = RepoOferta::findById($id);
        
        if (!$oferta) {
            Sesion::escribirSesion('error', 'Oferta no encontrada.');
            header('Location: /index.php?admin=ofertas');
        }

        echo $this->templates->render('Admin/editarOferta', [
            'oferta' => $oferta,
            'action' => 'guardareditaroferta',
            'error' => Sesion::leerSesion('error'),
            'old_input' => Sesion::leerSesion('old_input'),
        ]);
    }

    public function edit_() {
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            Sesion::escribirSesion('error', 'ID de oferta no proporcionado.');
            header('Location: /index.php?admin=ofertas');
        }

        $id = (int)$_POST['id'];

        $oferta = RepoOferta::findById($id);
        
        if (!$oferta) {
            Sesion::escribirSesion('error', 'Oferta no encontrada.');
            header('Location: /index.php?admin=ofertas');
        }

        echo $this->templates->render('Empresa/editarOferta', [
            'oferta' => $oferta,
            'action' => 'guardareditaroferta',
            'error' => Sesion::leerSesion('error'),
            'old_input' => Sesion::leerSesion('old_input'),
        ]);
    }   

    public function save_edit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?admin=ofertas');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        if (!$this->validator->ValidacionPasada()) {
            Sesion::escribirSesion('error', 'Por favor, corrige los errores del formulario.');
            Sesion::escribirSesion('old_input', $_POST);
            
            header("Location: /index.php?admin=editaroferta&id=$id");
        }

        $oferta = RepoOferta::findById($id);
        if (!$oferta) {
            Sesion::escribirSesion('error', 'Oferta a editar no encontrada.');
            header('Location: /index.php?admin=ofertas');
        }
        
        Adapter::DTOtoOferta($oferta, $_POST);

        if (RepoOferta::update($oferta)) {
            Sesion::escribirSesion('success', 'Oferta actualizada correctamente.');
        }

        header('Location: /index.php?admin=ofertas');
    }

    public function save_edit_() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?page=misofertas');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        if (!$this->validator->ValidacionPasada()) {
            Sesion::escribirSesion('error', 'Por favor, corrige los errores del formulario.');
            Sesion::escribirSesion('old_input', $_POST);
            
            header("Location: /index.php?page=editaroferta&id=$id");
        }

        $oferta = RepoOferta::findById($id);
        if (!$oferta) {
            Sesion::escribirSesion('error', 'Oferta a editar no encontrada.');
            header('Location: /index.php?page=misofertas');
        }
        
        Adapter::DTOtoOferta($oferta, $_POST);

        if (RepoOferta::update($oferta)) {
            Sesion::escribirSesion('success', 'Oferta actualizada correctamente.');
        }

        header('Location: /index.php?page=misofertas');
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Sesion::escribirSesion('error', 'Petición de borrado inválida.');
            header('Location: /index.php?page=misofertas');
        }

        $this->validator->Requerido('id');

        if (!$this->validator->ValidacionPasada()) {
            Sesion::escribirSesion('error', 'ID de oferta no proporcionado para el borrado.');
            header('Location: /index.php?page=misofertas');
        }
        
        $id = (int)$_POST['id'];
        
        if (RepoOferta::delete($id)) {
            Sesion::escribirSesion('success', 'Oferta y datos asociados eliminados correctamente.');
        } else {
            Sesion::escribirSesion('error', 'Error al intentar eliminar la oferta. Asegúrate de que existe.');
        }

        header('Location: /index.php?page=misofertas');
    }
}