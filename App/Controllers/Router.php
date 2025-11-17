<?php

namespace App\Controllers;

use League\Plates\Engine;
use App\Helpers\Sesion; 
use App\Helpers\Login;   
use App\Services\ApiAlumno;
use App\Services\ApiAuth;
use App\Services\ApiCiclo;
use App\Services\ApiFamilia;
use App\Controllers\AlumnoController;
use App\Controllers\EmpresaController;
use App\Controllers\AuthController;

class Router {

    protected $templates;

    public function __construct() {
        $this->templates = new Engine('../Views/'); 
    }

    public function run() {
        Sesion::abrirSesion(); 

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if (isset($_GET['api'])) {
            $api = strtolower($_GET['api']);
            $id = $_GET['id'] ?? null;
            $requestBody = file_get_contents('php://input');
            header('Content-Type: application/json');

            switch ($api) {
                case 'alumnos':
                    (new ApiAlumno())->handleRequest($requestMethod, $requestBody);
                    break;
                case 'familias':
                    (new ApiFamilia())->getAllFamilias();
                    break;
                case 'ciclos':
                    $familiaId = $_GET['familia_id'] ?? null;
                    (new ApiCiclo())->getCiclosByFamilia($familiaId);
                    break;
                default:
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Recurso API no encontrado.']);
                    break;
            }
            exit;
        }

        if (isset($_GET['admin'])) {
            if (!Login::estaLogeado()) {
                Sesion::escribirSesion('login_error', 'Debes iniciar sesión para acceder al área de administración.');
                header('Location: /index.php?page=login');
                exit;
            }
            if (Login::getLoggedInUserRol() != '1') {
                Sesion::escribirSesion('login_error', 'No tienes permisos para acceder a esta área.');
                header('Location: /index.php?page=home');
                exit;
            }
            $adminPath = strtolower($_GET['admin']);
            switch ($adminPath) {
                case '':
                case 'index':
                    echo $this->templates->render('Admin/dashboard');
                    break;
                case 'alumnos': 
                    echo $this->templates->render('Admin/listadoAlumnos');
                    break;
                case 'empresas': 
                    (new EmpresaController($this->templates))->list();
                    break;
                case 'invitarempresa':
                    (new EmpresaController($this->templates))->invite();
                    break;
                case 'guardarinvitarempresa':
                    (new EmpresaController($this->templates))->save_invite();
                    break;
                case 'editarempresa':
                    (new EmpresaController($this->templates))->edit();
                    break;
                case 'guardareditarempresa':
                    (new EmpresaController($this->templates))->save_edit();
                    break;
                case 'borrarempresa':
                    (new EmpresaController($this->templates))->delete();
                    break;
                case 'fichaempresa':
                    (new EmpresaController($this->templates))->showFicha();
                    break;
                case 'ofertas':
                    (new OfertaController($this->templates))->list();
                    break;
                case 'solicitudes':
                    echo $this->templates->render('Admin/listadoSolicitudes');
                    break;
                default:
                    http_response_code(404);
                    echo $this->templates->render('404');
                    break;
            }
            exit; 
        }

        if (isset($_GET['page'])) {
            $paginaPath = strtolower($_GET['page']);
            switch ($paginaPath) {
                case 'index': 
                    echo $this->templates->render('Landing/landingPage');
                    break;
                case 'registroalumno':
                    echo $this->templates->render('Landing/registroAlumno');
                    break;
                case 'registroalumnopost':
                    (new AuthController($this->templates))->registroAlumno();
                    break;
                case 'registroempresa':
                    echo $this->templates->render('Landing/registroEmpresa');
                    break;
                case 'registroempresapost':
                    (new AuthController($this->templates))->registroEmpresa();
                    break;
                case 'login':
                    if (Login::estaLogeado()) {
                        $rol = Login::getLoggedInUserRol();
                        if ($rol == '1') {
                            header('Location: /index.php?admin=dashboard');
                        }
                        if ($rol == '2') {
                            header('Location: /index.php?page=misofertas');
                        }
                        if ($rol == '3') {
                            header('Location: /index.php?page=missolicitudes');
                        }
                        exit;
                    }
                    echo $this->templates->render('Landing/login');
                    break;
                case 'loginpost':
                    (new AuthController($this->templates))->login();
                    break;
                case 'logout':
                    (new AuthController($this->templates))->logout();
                    break;
                case 'home':
                    if (!Login::estaLogeado()) {
                        header('Location: /index.php?page=login');
                        exit;
                    }
                    echo $this->templates->render('home', ['user_email' => Login::getLoggedInUserEmail()]);
                    break;
                default:
                    http_response_code(404);
                    echo $this->templates->render('404');
                    break;
            }
            exit;
        }

        echo $this->templates->render('Landing/landingPage');
    }
}