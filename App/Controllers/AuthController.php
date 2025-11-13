<?php

namespace App\Controllers;

use App\Models\User;
use App\Repositories\RepoUser;
use App\Helpers\Login;
use App\Helpers\Adapter;
use App\Helpers\Sesion;
use Exception;

class AuthController {
    // ESTE MÉTODO ES PARA EL ENDPOINT API (ej. llamado por JavaScript)
    public static function login(string $body) {
        header('Content-Type: application/json');
        $data = json_decode($body, true);

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Email o contraseña no proporcionados.']);
            exit;
        }

        try {
            $user = RepoUser::findUser($email);

            if ($user && password_verify($password, $user->password)) {
                $userDTO = Adapter::userToDTO($user);
                Login::login($userDTO); // Inicia sesión
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Login exitoso.', 'user' => $userDTO]);
            } else {
                http_response_code(401); // Unauthorized
                echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas.']);
            }
        } catch (Exception $e) {
            error_log("Error en AuthController::login (API): " . $e->getMessage());
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Ocurrió un error en el servidor.']);
        }
        exit;
    }

    // ESTE MÉTODO ES PARA EL FORMULARIO HTML (llamado por el Router para peticiones POST)
    public static function handleLoginPost() {
        Sesion::abrirSesion(); // Asegurarse de que la sesión esté iniciada

        // Verificamos si la petición es POST y si los datos están en $_POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            // $recuerdame = isset($_POST['recuerdame']); // Si quieres implementar "Recuérdame"

            if (empty($email) || empty($password)) {
                Sesion::escribirSesion('login_error', 'Por favor, introduce email y contraseña.');
                header('Location: /index.php?action=showlogin'); // Redirige al formulario con error
                exit;
            }

            try {
                $user = RepoUser::findUser($email);

                if ($user !== null) {
                    if (password_verify($password, $user->password)) {
                        $userDTO = Adapter::userToDTO($user);
                        Login::login($userDTO); // Inicia la sesión

                        // Redirigir a una página protegida (ej. el dashboard o inicio)
                        header('Location: /index.php?action=dashboard'); 
                        exit;
                    } else {
                        Sesion::escribirSesion('login_error', 'Contraseña incorrecta.');
                        header('Location: /index.php?action=showlogin');
                        exit;
                    }
                } else {
                    Sesion::escribirSesion('login_error', 'Usuario no encontrado.');
                    header('Location: /index.php?action=showlogin');
                    exit;
                }
            } catch (Exception $e) {
                error_log("Error en AuthController::handleLoginPost: " . $e->getMessage());
                Sesion::escribirSesion('login_error', 'Ocurrió un error inesperado. Intenta de nuevo.');
                header('Location: /index.php?action=showlogin');
                exit;
            }
        } else {
            // Si no es un POST (ej. alguien intenta acceder directamente con GET)
            header('Location: /index.php?action=showlogin');
            exit;
        }
    }

    // Método para registro API (si lo mantienes)
    public static function register(string $body) { /* ... */ }
    
    // Método para logout API (si lo mantienes)
    public static function logout() { 
        Sesion::abrirSesion(); // Asegurarse de que la sesión esté iniciada para logout API
        Login::logout();
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Sesión cerrada exitosamente.']);
        exit;
    }
    
    // Método para checkAuth API (si lo mantienes)
    public static function checkAuth() { 
        Sesion::abrirSesion(); // Asegurarse de que la sesión esté iniciada para checkAuth
        header('Content-Type: application/json');
        if (Login::estaLogeado()) {
            http_response_code(200);
            echo json_encode(['success' => true, 'logged_in' => true, 'user_email' => Login::getLoggedInUserEmail(), 'user_rol' => Login::getLoggedInUserRol()]);
        } else {
            http_response_code(200); // 200 si solo informamos el estado
            echo json_encode(['success' => true, 'logged_in' => false, 'message' => 'No hay sesión activa.']);
        }
        exit;
    }

    // Este es el método de logout para el frontend PHP (redirecciona)
    public static function handleLogout() {
        Sesion::abrirSesion();
        Login::logout();
        Sesion::escribirSesion('login_message', 'Has cerrado sesión correctamente.');
        header('Location: /index.php?action=showlogin');
        exit;
    }
}