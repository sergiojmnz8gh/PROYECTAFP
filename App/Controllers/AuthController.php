<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Alumno;
use App\Models\Empresa;
use App\Repositories\RepoUser;
use App\Repositories\RepoAlumno;
use App\Repositories\RepoEmpresa;
use App\Helpers\Login;
use App\Helpers\Adapter;
use App\Helpers\Sesion;
use App\Helpers\Security;
use Exception;

class AuthController {

    public static function login() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                Sesion::escribirSesion('login_error', 'Por favor, introduce email y contraseña.');
                header('Location: /index.php?page=login');
                exit;
            }

            try {
                $user = RepoUser::findUser($email);
                Sesion::escribirSesion('rol_id', $user->rol_id);

                if ($user !== null) {
                    if (Security::verifyPassword($password, $user->password)) {
                        $userDTO = Adapter::userToDTO($user);
                        Login::login($userDTO);
                        Sesion::escribirSesion('welcome_message', '¡Bienvenido de nuevo, ' . $user->email . '!');
                        if ($user->rol_id == 3) {
                            header('Location: /index.php?page=missolicitudes');
                        }
                        if ($user->rol_id == 2) {
                            header('Location: /index.php?page=misofertas');
                        }
                        if ($user->rol_id == 1) {
                            header('Location: /index.php?admin=dashboard');
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
        } else {
            header('Location: /index.php?page=login');
            exit;
        }
    }

    public static function registroAlumno() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $ciclo = $_POST['ciclo_id'] ?? '';
            $telefono = $_POST['telefono'] ?? null;
            $direccion = $_POST['direccion'] ?? null;
            $foto = $_FILES['foto'] ?? null;
            $cv = $_FILES['cv'] ?? null;


            if (empty($email) || empty($password) || empty($nombre) || empty($apellidos)) {
                Sesion::escribirSesion('registro_error', 'Faltan campos obligatorios para el registro de alumno.');
                header('Location: /index.php?page=registroalumno');
                exit;
            }

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
        } else {
            header('Location: /index.php?page=registroalumno');
            exit;
        }
    }

    public static function registroEmpresa() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $reppassword = $_POST['reppassword'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $telefono = $_POST['telefono'] ?? null;
            $direccion = $_POST['direccion'] ?? null;
            $logo = $_FILES['logo'] ?? null;

            if (empty($email) || empty($password) || empty($nombre)) {
                Sesion::escribirSesion('registro_error', 'Faltan campos obligatorios para el registro de empresa.');
                header('Location: /index.php?page=registroempresa');
                exit;
            }

            if ($password !== $reppassword) {
                Sesion::escribirSesion('registro_error', 'Las contraseñas no coinciden.');
                header('Location: /index.php?page=registroempresa');
                exit;
            }

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
                $empresa->logo = $logo;
                $empresa->email = $email;
                $empresa->activo = true;

                RepoEmpresa::create($empresa, $hashedPassword);
                $newEmpresa = RepoEmpresa::findByEmail($email);
                if ($newEmpresa) {
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
        } else {
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