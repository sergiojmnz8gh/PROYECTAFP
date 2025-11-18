<?php

namespace App\Controllers;

use App\Models\Alumno;
use App\Models\Empresa;
use App\Repositories\RepoUser;
use App\Repositories\RepoAlumno;
use App\Repositories\RepoEmpresa;
use App\Helpers\Login;
use App\Helpers\Adapter;
use App\Helpers\Sesion;
use App\Helpers\Security;
use App\Helpers\Validator;
use App\Services\Correos;
use Exception;

class AuthController {

    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?page=login');
            exit;
        }
        
        $validator = new Validator();
        
        $validator->Requerido('email');
        $validator->Requerido('password');

        if (!$validator->ValidacionPasada()) {
            Sesion::escribirSesion('login_error', 'Por favor, introduce email y contraseña.');
            header('Location: /index.php?page=login');
            exit;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $user = RepoUser::findUser($email);

            if ($user !== null) {
                if (Security::verifyPassword($password, $user->password)) {
                    $userDTO = Adapter::userToDTO($user);
                    Login::login($userDTO);
                    Sesion::escribirSesion('welcome_message', '¡Bienvenido de nuevo, ' . $user->email . '!');
                    
                    if ($user->rol_id == 3) {
                        header('Location: /index.php?page=missolicitudes');
                    } elseif ($user->rol_id == 2) {
                        header('Location: /index.php?page=misofertas');
                    } elseif ($user->rol_id == 1) {
                        header('Location: /index.php?admin=dashboard');
                    } else {
                        header('Location: /index.php');
                    }
                    exit;
                } else {
                    Sesion::escribirSesion('login_error', 'Contraseña incorrecta.');
                    header('Location: /index.php?page=login');
                    exit;
                }
            } else {
                Sesion::escribirSesion('login_error', 'Usuario no encontrado.');
                header('Location: /index.php?page=login');
                exit;
            }
        } catch (Exception $e) {
            error_log("Error de login: " . $e->getMessage());
            Sesion::escribirSesion('login_error', 'Ocurrió un error inesperado. Intenta de nuevo.');
            header('Location: /index.php?page=login');
            exit;
        }
    }

    public static function registroAlumno() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?page=registroalumno');
            exit;
        }

        $validator = new Validator();
        
        $validator->Requerido('email');
        $validator->Email('email');
        $validator->Requerido('password');
        $validator->Requerido('nombre');
        $validator->Requerido('apellidos');
        $validator->Requerido('ciclo_id');
        $validator->Requerido('telefono');
        $validator->Patron('telefono', '/^[0-9]{9}$/');
        $validator->Requerido('direccion');
        
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $validator->Requerido('foto_file_upload');
        }
        if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
            $validator->Requerido('cv_file_upload');
        }
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK && $_FILES['cv']['type'] !== 'application/pdf') {
            $validator->ValidaConFuncion('cv_file_type', function() { return false; }, 'El CV debe ser un archivo PDF.');
        }


        if (!$validator->ValidacionPasada()) {
            Sesion::escribirSesion('registro_error', 'Por favor, corrige los errores del formulario de registro.');
            Sesion::escribirSesion('old_input', $_POST);
            header('Location: /index.php?page=registroalumno');
            exit;
        }
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $ciclo = $_POST['ciclo_id'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $foto = $_FILES['foto'];
        $cv = $_FILES['cv'];

        try {
            if (RepoUser::findUser($email) !== null) {
                Sesion::escribirSesion('registro_error', 'El email ya está registrado.');
                header('Location: /index.php?page=registroalumno');
                exit;
            }

            $hashedPassword = Security::hashPassword($password);

            $alumno = new Alumno();
            $alumno->nombre = $nombre;
            $alumno->apellidos = $apellidos;
            $alumno->ciclo_id = $ciclo;
            $alumno->telefono = $telefono;
            $alumno->direccion = $direccion;
            $alumno->email = $email;
            $alumno->activo = true;

            RepoAlumno::create($alumno, $hashedPassword);

            $newAlumno = RepoAlumno::findByEmail($email);
            if ($newAlumno) {
                Correos::enviarCorreoRegistro($email);
                $userDTO = Adapter::userToDTO(RepoUser::findUser($email));
                
                move_uploaded_file($foto["tmp_name"], '../public/img/alumnos/' . $newAlumno->user_id . '.jpg');
                move_uploaded_file($cv["tmp_name"], '../resources/cvs/' . $newAlumno->user_id . '.pdf');

                Login::login($userDTO);
                Sesion::escribirSesion('welcome_message', '¡Bienvenido ' . $newAlumno->nombre . ', tu registro ha sido exitoso!');
                header('Location: /index.php?page=missolicitudes');
                exit;
            } else {
                Sesion::escribirSesion('registro_error', 'Error al registrar el alumno en la base de datos.');
                header('Location: /index.php?page=registroalumno');
                exit;
            }
        } catch (Exception $e) {
            error_log("Error de registro de alumno: " . $e->getMessage());
            Sesion::escribirSesion('registro_error', 'Ocurrió un error inesperado durante el registro.');
            header('Location: /index.php?page=registroalumno');
            exit;
        }
    }

    public static function registroEmpresa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?page=registroempresa');
            exit;
        }
        
        $validator = new Validator();
        
        $validator->Requerido('email');
        $validator->Email('email');
        $validator->Requerido('password');
        $validator->Requerido('reppassword');
        $validator->Requerido('nombre');
        $validator->Requerido('telefono');
        $validator->Patron('telefono', '/^[0-9]{9}$/');
        $validator->Requerido('direccion');
        
        if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            $validator->Requerido('logo_file_upload');
        }

        if ($_POST['password'] !== $_POST['reppassword']) {
            $validator->ValidaConFuncion('reppassword', function() { return false; }, 'Las contraseñas no coinciden.');
        }

        if (!$validator->ValidacionPasada()) {
            Sesion::escribirSesion('registro_error', 'Por favor, corrige los errores del formulario de registro.');
            Sesion::escribirSesion('old_input', $_POST);
            header('Location: /index.php?page=registroempresa');
            exit;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $logo = $_FILES['logo'];

        try {
            if (RepoUser::findUser($email) !== null) {
                Sesion::escribirSesion('registro_error', 'El email ya está registrado.');
                header('Location: /index.php?page=registroempresa');
                exit;
            }

            $hashedPassword = Security::hashPassword($password);

            $empresa = new Empresa();
            $empresa->nombre = $nombre;
            $empresa->telefono = $telefono;
            $empresa->direccion = $direccion;
            $empresa->email = $email;
            $empresa->activo = true;

            RepoEmpresa::create($empresa, $hashedPassword);
            $newEmpresa = RepoEmpresa::findByEmail($email);
            if ($newEmpresa) {
                Correos::enviarCorreoRegistro($email);
                $userDTO = Adapter::userToDTO(RepoUser::findUser($email));

                move_uploaded_file($logo["tmp_name"], '../public/img/empresas/' . $newEmpresa->user_id . '.jpg');
                Login::login($userDTO);
                Sesion::escribirSesion('welcome_message', '¡Bienvenida ' . $newEmpresa->nombre . ', tu registro ha sido exitoso!');
                header('Location: /index.php?page=misofertas');
                exit;
            } else {
                Sesion::escribirSesion('registro_error', 'Error al registrar la empresa en la base de datos.');
                header('Location: /index.php?page=registroempresa');
                exit;
            }
        } catch (Exception $e) {
            error_log("Error de registro de empresa: " . $e->getMessage());
            Sesion::escribirSesion('registro_error', 'Ocurrió un error inesperado durante el registro.');
            header('Location: /index.php?page=registroempresa');
            exit;
        }
    }
    
    public static function logout() {
        Login::logout();
        Sesion::cerrarSesion();
        Sesion::escribirSesion('login_message', 'Has cerrado sesión correctamente.');
        header('Location: /index.php?page=login');
        exit;
    }
}