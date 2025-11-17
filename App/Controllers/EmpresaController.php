<?php

namespace App\Controllers;

use League\Plates\Engine;
use App\Helpers\Sesion; 
use App\Repositories\RepoEmpresa;
use App\Helpers\Adapter; 
use App\Helpers\Validator;

class EmpresaController {

    protected $templates;
    protected $validator;

    public function __construct(Engine $templates) {
        $this->templates = $templates;
        $this->validator = new Validator();
    }

    public function list() {
        $buscarEmpresa = $_GET['buscarempresa'] ?? null;
        $ordenarPor = $_GET['ordenarpor'] ?? 'id';
        $orden = $_GET['orden'] ?? 'ASC';
        
        $empresas = RepoEmpresa::findAll($buscarEmpresa, $ordenarPor, $orden);

        echo $this->templates->render('Admin/listadoEmpresas', [
            'empresas' => $empresas,
            'error' => Sesion::leerSesion('error'),
            'success' => Sesion::leerSesion('success'),
        ]);
    }
    
    public function invite() {
        echo $this->templates->render('Admin/invitacionEmpresa', [
            'empresa' => null,
            'action' => 'guardarinvitarempresa'
        ]);
    }

    public function save_invite() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?admin=empresas');
            exit;
        }

        $this->validator->Requerido('nombre');
        $this->validator->CadenaRango('nombre', 100, 3);
        
        $this->validator->Requerido('email');
        $this->validator->Email('email');
        
        $this->validator->Requerido('password');
        
        $this->validator->Requerido('telefono');
        $this->validator->Patron('telefono', '/^[0-9]{9}$/');

        if (!$this->validator->ValidacionPasada()) {
            Sesion::escribirSesion('error', 'Por favor, corrige los errores del formulario.');
            Sesion::escribirSesion('old_input', $_POST);
            header('Location: /index.php?admin=invitarempresa');
            exit;
        }

        $empresa = Adapter::DTOtoEmpresa(null, $_POST);
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if (RepoEmpresa::create($empresa, $hashedPassword)) {
            Sesion::escribirSesion('success', 'Empresa registrada correctamente.');
        } else {
            Sesion::escribirSesion('error', 'Error al crear la empresa. Puede que el email ya exista.');
        }

        header('Location: /index.php?admin=empresas');
        exit;
    }
    
    public function edit() {
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            Sesion::escribirSesion('error', 'ID de empresa no proporcionado.');
            header('Location: /index.php?admin=empresas');
            exit;
        }

        $id = (int)$_POST['id'];

        $empresa = RepoEmpresa::findById($id);
        
        if (!$empresa) {
            Sesion::escribirSesion('error', 'Empresa no encontrada.');
            header('Location: /index.php?admin=empresas');
            exit;
        }

        echo $this->templates->render('Admin/editarEmpresa', [
            'empresa' => $empresa,
            'action' => 'guardareditarrempresa',
            'error' => Sesion::leerSesion('error'),
            'old_input' => Sesion::leerSesion('old_input'),
        ]);
    }

    public function save_edit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?admin=empresas');
            exit;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        $this->validator->Requerido('id');
        
        $this->validator->Requerido('nombre');
        $this->validator->CadenaRango('nombre', 100, 3);
        
        $this->validator->Requerido('email');
        $this->validator->Email('email');
        
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $this->validator->CadenaRango('password', 30, 6); 
        }

        $this->validator->Requerido('telefono');
        $this->validator->Patron('telefono', '/^[0-9]{9}$/');
        
        if (!$this->validator->ValidacionPasada()) {
            Sesion::escribirSesion('error', 'Por favor, corrige los errores del formulario.');
            Sesion::escribirSesion('old_input', $_POST);
            
            header("Location: /index.php?admin=editarempresa&id=$id");
            exit;
        }

        $empresa = RepoEmpresa::findById($id);
        if (!$empresa) {
            Sesion::escribirSesion('error', 'Empresa a editar no encontrada.');
            header('Location: /index.php?admin=empresas');
            exit;
        }
        
        Adapter::DTOtoEmpresa($empresa, $_POST);

        $newHashedPassword = null;
        if (!empty($_POST['password'])) {
            $newHashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        if (RepoEmpresa::update($empresa, $newHashedPassword)) {
            Sesion::escribirSesion('success', 'Empresa actualizada correctamente.');
        } else {
            Sesion::escribirSesion('error', 'Error al actualizar la empresa. El email podría estar en uso.');
        }

        header('Location: /index.php?admin=empresas');
        exit;
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Sesion::escribirSesion('error', 'Petición de borrado inválida.');
            header('Location: /index.php?admin=empresas');
            exit;
        }

        $this->validator->Requerido('id');

        if (!$this->validator->ValidacionPasada()) {
            Sesion::escribirSesion('error', 'ID de empresa no proporcionado para el borrado.');
            header('Location: /index.php?admin=empresas');
            exit;
        }
        
        $id = (int)$_POST['id'];
        
        if (RepoEmpresa::delete($id)) {
            Sesion::escribirSesion('success', 'Empresa y datos asociados eliminados correctamente.');
        } else {
            Sesion::escribirSesion('error', 'Error al intentar eliminar la empresa. Asegúrate de que existe.');
        }

        header('Location: /index.php?admin=empresas');
        exit;
    }

    public function showFicha() {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            Sesion::escribirSesion('error', 'ID de empresa no proporcionado.');
            header('Location: /index.php?admin=empresas');
            exit;
        }
        
        $id = (int)$_GET['id'];

        $empresa = RepoEmpresa::findById($id);
        
        if (!$empresa) {
            Sesion::escribirSesion('error', 'Empresa no encontrada.');
            header('Location: /index.php?admin=empresas');
            exit;
        }

        echo $this->templates->render('Admin/fichaEmpresa', [
            'empresa' => $empresa
        ]);
    }
}