<?php

namespace App\Services;

use App\Repositories\RepoSolicitud;
use App\Helpers\Adapter;

class Apisolicitud {

    public function __construct() {}

    public function handleRequest($requestMethod, $requestBody) {
        header('Content-Type: application/json');

        switch ($requestMethod) {
            case 'GET':
                $this->getSizedList($requestBody);
                break;
            case 'POST':
                $this->savesolicitud($requestBody);
                break;
            case 'PUT':
                $this->editsolicitud($requestBody);
                break;
            case 'DELETE':
                $this->deletesolicitud($requestBody);
                break;
            default:
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
                break;
        }
        exit;
    }

    function getSolicitud($requestBody) {
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

    function getSizedList($requestBody) {
        $pagination = json_decode($requestBody, true);
        $solicitudes = RepoSolicitud::findSizedList($pagination);
        $solicitudesDTO = Adapter::AllsolicitudToDTO($solicitudes);
        http_response_code(200);
        echo json_encode($solicitudesDTO);
    }

}