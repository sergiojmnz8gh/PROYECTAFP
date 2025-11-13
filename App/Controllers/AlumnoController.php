<?php

namespace App\Controllers;

use App\Repositories\RepoAlumno;
use App\Helpers\Adapter; // Asegúrate de que el namespace y la ruta sean correctos
use App\Models\Alumno;    // Asegúrate de que el namespace y la ruta sean correctos
use App\Models\User;      // Si tu Adapter o RepoAlumno lo necesitan


class AlumnoController
{
    public static function getFullList()
    {
        header('Content-Type: application/json');
        http_response_code(200);
        $alumnos = RepoAlumno::findAll();
        // Asumiendo que Adapter::AllAlumnoToDTO devuelve un array de DTOs
        $alumnosDTO = Adapter::AllAlumnoToDTO($alumnos); 
        echo json_encode($alumnosDTO);
    }

    public static function getSizedList()
    {
        // Implementar lógica de paginación aquí
        // Por ahora, lo dejamos vacío según tu script original
        http_response_code(501); // Not Implemented
        echo json_encode(['success' => false, 'message' => 'Paginación no implementada.']);
    }

    public static function getAlumnoById($id) {
        header('Content-Type: application/json');
        $alumno = RepoAlumno::findById($id);
        if ($alumno) {
            http_response_code(200);
            $alumnoDTO = Adapter::alumnoToDTO($alumno);
            echo json_encode($alumnoDTO);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Alumno no encontrado.']);
        }
    }


    public static function saveAlumno($body)
    {
        header('Content-Type: application/json');
        $decodedBody = json_decode($body, true);
        
        // Aquí deberías crear un Alumno (Model) y potencialmente un User (Model)
        // Ya que un Alumno tiene un User asociado (user_id en alumnos)
        // Tu Adapter::DTOtoAlumno debería manejar la creación del User y el Alumno.
        $alumno = Adapter::DTOtoAlumno($decodedBody);

        $dbResponse = RepoAlumno::create($alumno); // Este método debería devolver el objeto Alumno con su ID y el User con su ID

        if ($dbResponse !== false) {
            http_response_code(201); // 201 Created para POST
            $alumnoDTO = Adapter::alumnoToDTO($dbResponse);
            echo json_encode([
                'success' => true,
                'alumno' => $alumnoDTO
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Error al guardar el alumno.']);
        }
    }

    public static function deleteAlumno($body)
    {
        header('Content-Type: application/json');
        $data = json_decode($body, true);
        $id = $data['id'] ?? null; // Asegúrate de que el ID viene en el body
        
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de alumno no proporcionado.']);
            return;
        }

        if (RepoAlumno::delete($id)) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Alumno eliminado correctamente.']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Alumno no encontrado o error al eliminar.']);
        }
    }

    public static function editAlumno($body)
    {
        header('Content-Type: application/json');
        $data = json_decode($body, true);
        
        // Tu método update de RepoAlumno probablemente espera un objeto Alumno
        // o un array asociativo con todos los datos necesarios para actualizar.
        // Asegúrate de que $data contenga el ID del alumno a actualizar.
        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de alumno no proporcionado para la edición.']);
            return;
        }

        // Si tu RepoAlumno::update espera un objeto Alumno:
        $alumno = Adapter::DTOtoAlumno($data); // Reutiliza el adapter para convertir a objeto
        $alumno->id = $data['id']; // Asegúrate de asignar el ID para la actualización

        if (RepoAlumno::update($alumno)) { // Pasa el objeto Alumno
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Alumno actualizado correctamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el alumno.']);
        }
    }
}