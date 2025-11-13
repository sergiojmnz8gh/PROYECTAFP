<?php

namespace App\Controllers;

use League\Plates\Engine;
use App\Repositories\RepoEmpresa;
use App\Repositories\RepoOferta;
use App\Controllers\AlumnoController; 
use App\Helpers\Sesion; 
use App\Helpers\Login;   
use App\Controllers\AuthController; 

class Router {

    public function run() {
        Sesion::abrirSesion(); 

        // Ajusta esta ruta si tu carpeta Views no está directamente en el directorio padre de Controllers
        // Por ejemplo: si Router está en App/Controllers y Views en la raíz del proyecto, usa:
        // $templates = new Engine(__DIR__ . '/../../Views/');
        $templates = new Engine('../Views/'); 

        // --- 1. Lógica del API Router (intercepta peticiones con ?api=...) ---
        if (isset($_GET['api'])) {
            $api_endpoint = strtolower($_GET['api']);
            $request_method = $_SERVER['REQUEST_METHOD'];
            $id = $_GET['id'] ?? null; 

            switch ($api_endpoint) {
                // Aquí, estas rutas API sí esperan un JSON en el body
                case 'login':
                    AuthController::login(file_get_contents('php://input'));
                    exit;
                case 'logout':
                    AuthController::logout();
                    exit;
                case 'check-auth':
                    AuthController::checkAuth();
                    exit;
                case 'familias':
                    FamiliaController::getAllFamilias();
                    exit;
                case 'ciclos':
                    // Si el ID se pasa en la URL, ej: ?api=ciclos&familia_id=1
                    $familiaId = $_GET['familia_id'] ?? null;
                    if ($familiaId) {
                        CicloController::getCiclosByFamilia($familiaId);
                    } else {
                        CicloController::getAllCiclos();
                    }
                    exit;
            }

            if ($api_endpoint === 'alumnos') {
                switch ($request_method) {
                    case 'GET':
                        if ($id) {
                            AlumnoController::getAlumnoById($id);
                        } else {
                            AlumnoController::getFullList();
                        }
                        break;
                    case 'POST':
                        AlumnoController::saveAlumno(file_get_contents('php://input'));
                        break;
                    case 'PUT':
                        AlumnoController::editAlumno(file_get_contents('php://input'));
                        break;
                    case 'DELETE':
                        AlumnoController::deleteAlumno(file_get_contents('php://input'));
                        break;
                    default:
                        http_response_code(405);
                        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
                        break;
                }
                exit; 
            }
            
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Ruta API no encontrada.']);
            exit; 
        }

        // --- 2. Lógica del Router de Vistas (si no fue una petición API) ---
        // Manejamos nuestras nuevas acciones de login/logout/dashboard primero
        if (isset($_GET['action'])) {
            $action = strtolower($_GET['action']);
            switch ($action) {
                case 'showlogin':
                    if (Login::estaLogeado()) {
                        header('Location: /index.php?action=dashboard');
                        exit;
                    }
                    // ASUMO que tu vista de login ahora está en Views/login.php
                    // Si está en Views/Landing/login.php, cambia a 'Landing/login'
                    echo $templates->render('Landing/login'); 
                    exit; 
                case 'login': // <<-- CORRECCIÓN IMPORTANTE AQUÍ -->>
                    // Para el formulario HTML, llamamos a handleLoginPost(), no al login() de la API
                    AuthController::handleLoginPost();
                    exit;
                case 'logout':
                    AuthController::handleLogout();
                    exit;
                case 'dashboard':
                    if (!Login::estaLogeado()) {
                        Sesion::escribirSesion('login_error', 'Debes iniciar sesión para acceder a esta área.');
                        header('Location: /index.php?action=showlogin');
                        exit;
                    }
                    echo $templates->render('dashboard', ['user_email' => Login::getLoggedInUserEmail()]);
                    exit;
            }
        }

        // --- Protección de tus Vistas de Admin ---
        if (isset($_GET['admin'])) {
            // Protección general para todas las rutas de /admin
            if (Login::estaLogeado()) { 
                Sesion::escribirSesion('login_error', 'Debes iniciar sesión para acceder al área de administración.');
                header('Location: /index.php?action=showlogin');
                exit;
            }
            // Opcional: proteger por rol específico de administrador
            // if (Login::getLoggedInUserRol() !== 1) { // Asumiendo rol 1 es admin
            //     Sesion::escribirSesion('login_error', 'No tienes permisos para acceder a esta área.');
            //     header('Location: /index.php?action=dashboard'); // Redirige a un dashboard normal o a home
            //     exit;
            // }

            $path = $_GET['admin'];
            switch (strtolower($path)) {
                case '':
                case 'index': 
                    // Renderiza un dashboard específico de admin, no la landingPage
                    echo $templates->render('Admin/adminDashboard'); 
                    break;
                case 'alumnos': 
                    echo $templates->render('Admin/listadoAlumnos');
                    break;
                case 'empresas': 
                    $empresas= RepoEmpresa::findAll();
                    echo $templates->render('Admin/listadoEmpresas', [
                        'empresas' => $empresas
                    ]);
                    break;
                case 'invitacionempresa':
                    echo $templates->render('Admin/invitacionEmpresa');
                    break;
                case 'listadoOfertas':
                    $ofertas = RepoOferta::findAll();
                    echo $templates->render('Admin/listadoOfertas', [
                        'ofertas' => $ofertas
                    ]);
                    break;
                case 'listadoSolicitudes':
                    echo $templates->render('Admin/listadoSolicitudes');
                    break;
                default:
                    http_response_code(404);
                    echo $templates->render('404');
                    break;
            }
            exit; 
        }
        
        // --- Tu Lógica Original para $_GET['pagina'] (Landing pages) ---
        if (isset($_GET['pagina'])) {
            $path = $_GET['pagina'];
            switch (strtolower($path)) {
                case 'index': 
                    echo $templates->render('Landing/landingPage');
                    break;
                case 'registroalumno':
                    echo $templates->render('Landing/registroAlumno');
                    break;
                case 'registroempresa':
                    echo $templates->render('Landing/registroEmpresa');
                    break;
                case 'login': 
                    header('Location: /index.php?action=showlogin');
                    exit;
                default:
                    http_response_code(404);
                    echo $templates->render('404');
                    break;
            }
        }
        else {
            echo $templates->render('Landing/landingPage');
        }
    }
}