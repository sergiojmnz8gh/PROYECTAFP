<?php

namespace App\Services;

use App\Repositories\RepoAlumno;
use App\Helpers\Adapter;
use App\Helpers\Security;

class ApiAlumno {

    public function __construct() {}

    public function handleRequest($requestMethod, $requestBody) {
        header('Content-Type: application/json');

        switch ($requestMethod) {
            case 'GET':
                $this->getFullList();
                break;
            case 'POST':
                $this->saveAlumno($requestBody);
                break;
            case 'PUT':
                $this->editAlumno($requestBody);
                break;
            case 'DELETE':
                $this->deleteAlumno($requestBody);
                break;
            default:
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
                break;
        }
        exit;
    }

    function getAlumno($requestBody) {
        $id = $requestBody['id'] ?? null;
        $alumno = RepoAlumno::findById($id);
        if ($alumno) {
            $alumnoDTO = Adapter::alumnoToDTO($alumno);
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'alumno' => $alumnoDTO,
                'message' => 'Alumno obtenido con éxito.'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Alumno no encontrado.']);
        }
    }

    function getFullList() {
        $alumnos = RepoAlumno::findAll();
        $alumnosDTO = Adapter::AllAlumnoToDTO($alumnos);
        http_response_code(200);
        echo json_encode($alumnosDTO);
    }

    function getSizedList($requestBody) {
        $filtersAndPagination = json_decode($requestBody, true);
        $alumnos = RepoAlumno::findSizedList($filtersAndPagination);
        $alumnosDTO = Adapter::AllAlumnoToDTO($alumnos);
        http_response_code(200);
        echo json_encode($alumnosDTO);
    }

    function saveAlumno($requestBody) {
        $decodedBody = json_decode($requestBody, true);
        $hashedPassword = Security::hashPassword($decodedBody['password']);
        unset($decodedBody['password']);
        $alumno = Adapter::DTOtoAlumno($decodedBody);
        $dbResponse = RepoAlumno::create($alumno, $hashedPassword);

        if ($dbResponse !== false) {
            $alumnoDTO = Adapter::alumnoToDTO($dbResponse); 
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'alumno' => $alumnoDTO,
                'message' => 'Alumno creado con éxito.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false]);
        }
    }

    function editAlumno($requestBody) {
        $decodedBody = json_decode($requestBody, true);
        $id = $decodedBody['id'] ?? null;
        if ($id == null) {
            http_response_code(400);
            echo json_encode(['success' => false]);
            return;
        }

        $existAlumno = RepoAlumno::findById($id);
        if (!$existAlumno) {
            http_response_code(404);
            echo json_encode(['success' => false]);
            return;
        }
        
        $newHashedPassword = null;
        if (isset($decodedBody['password']) && !empty($decodedBody['password'])) {
            $newHashedPassword = Security::hashPassword($decodedBody['password']);
            unset($decodedBody['password']);
        }
        $existAlumno->nombre = $decodedBody['nombre'] ?? $existAlumno->nombre;
        $existAlumno->apellidos = $decodedBody['apellidos'] ?? $existAlumno->apellidos;
        $existAlumno->telefono = $decodedBody['telefono'] ?? $existAlumno->telefono;
        $existAlumno->foto = $decodedBody['foto'] ?? $existAlumno->foto;
        $existAlumno->email = $decodedBody['email'] ?? $existAlumno->email;
        $existAlumno->activo = $decodedBody['activo'] ?? $existAlumno->activo;

        $dbResponse = RepoAlumno::update($existAlumno, $newHashedPassword);
        if ($dbResponse !== false) {
            $alumnoDTO = Adapter::alumnoToDTO($dbResponse); 
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'alumno' => $alumnoDTO,
                'message' => 'Alumno actualizado con éxito.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false]);
        }
    }
    function deleteAlumno($requestBody) {
        $decodedBody = json_decode($requestBody, true);
        $id = $decodedBody['id'] ?? null;
        if ($id == null) {
            http_response_code(400);
            echo json_encode(['success' => false]);
            return;
        }

        if (RepoAlumno::delete($id)) {
            http_response_code(200);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false]);
        }
    }
}