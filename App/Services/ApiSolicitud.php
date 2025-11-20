<?php

namespace App\Services;

use App\Repositories\RepoSolicitud;
use App\Models\Solicitud;
use App\Repositories\RepoAlumno;
use App\Helpers\Login;
use App\Helpers\Adapter;
use Exception;

class Apisolicitud {

    public function __construct() {}

    public function handleRequest($requestMethod, $requestBody, $GET = null) {
        header('Content-Type: application/json');

        switch ($requestMethod) {
            case 'GET':
                if (isset($GET['alumnoId'])) {
                    $this->getByAlumnoId($GET);
                } elseif (isset($GET['ofertaId'])) {
                    $this->getByOfertaId($GET);
                } else {
                    $this->getSizedList($requestBody);
                }
                break;
            case 'POST':
                $this->savesolicitud($requestBody);
                break;
            case 'PUT':
                break;
            case 'DELETE':
                break;
            default:
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
                break;
        }
        exit;
    }

    function getSolicitud($requestBody) {
        header('Content-Type: application/json');
        $id = $requestBody['id'] ?? null;
        $solicitud = Reposolicitud::findById($id);
        if ($solicitud) {
            $solicitudDTO = Adapter::solicitudToDTO($solicitud);
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'solicitud' => $solicitudDTO,
                'message' => 'solicitud obtenido con éxito.'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'solicitud no encontrado.']);
        }
    }

    function getByAlumnoId($GET) {
        header('Content-Type: application/json');
        $alumnoId = $GET['alumnoId'];
        $solicitudes = RepoSolicitud::findByAlumnoId($alumnoId);
        $solicitudesDTO = Adapter::AllSolicitudToDTO($solicitudes);
        http_response_code(200);
        echo json_encode($solicitudesDTO);
    }

    function getByOfertaId($GET) {
        header('Content-Type: application/json');
        $ofertaId = $GET['ofertaId'];
        $solicitudes = RepoSolicitud::findByOfertaId($ofertaId);
        $solicitudesDTO = Adapter::AllSolicitudToDTO($solicitudes);
        http_response_code(200);
        echo json_encode($solicitudesDTO);
    }

    function getSizedList($requestBody) {
        header('Content-Type: application/json');
        $pagination = json_decode($requestBody, true);
        $solicitudes = RepoSolicitud::findSizedList($pagination);
        $solicitudesDTO = Adapter::AllSolicitudToDTO($solicitudes);
        http_response_code(200);
        echo json_encode($solicitudesDTO);
    }

    function saveSolicitud($requestBody) {
        header('Content-Type: application/json');
        $decodedBody = json_decode($requestBody, true);
        $ofertaId = $decodedBody['oferta_id'];
        $userId = Login::getLoggedInUserId();
        $alumno = RepoAlumno::findByUserId($userId);
        $alumnoId = $alumno->id;

        if ($ofertaId == null || $alumnoId == null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
            return;
        } else {
            $existeSolicitud = RepoSolicitud::findByOfertaId($ofertaId);
            if ($existeSolicitud) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Ya existe una solicitud para esta oferta.']);
                return;
            }
        }

        try {
            $newSolicitud = new Solicitud();
            $newSolicitud->oferta_id = $ofertaId;
            $newSolicitud->alumno_id = $alumnoId;

            RepoSolicitud::create($newSolicitud);
            header('Location: /index.php?page=missolicitudes');
        }
        catch (Exception $e) {
            error_log("Error de creación de solicitud: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear la solicitud.']);
        }
    }
}