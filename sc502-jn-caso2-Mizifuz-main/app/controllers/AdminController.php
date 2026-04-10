<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/Taller.php';

class AdminController
{
    private $solicitudModel;
    private $tallerModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->solicitudModel = new Solicitud($db);
        $this->tallerModel = new Taller($db);
    }

    public function solicitudes()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        require __DIR__ . '/../views/admin/solicitudes.php';
    }

    public function getSolicitudesJson()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode([]);
            exit;
        }

        $solicitudes = $this->solicitudModel->getPendientes();
        echo json_encode($solicitudes);
        exit;
    }

    public function aprobar()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            exit;
        }

        $solicitudId = isset($_POST['id_solicitud']) ? (int)$_POST['id_solicitud'] : 0;

        if ($solicitudId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Solicitud inválida']);
            exit;
        }

        $solicitud = $this->solicitudModel->getById($solicitudId);

        if (!$solicitud) {
            echo json_encode(['success' => false, 'error' => 'La solicitud no existe']);
            exit;
        }

        if ($solicitud['estado'] !== 'pendiente') {
            echo json_encode(['success' => false, 'error' => 'La solicitud ya fue procesada']);
            exit;
        }

        $taller = $this->tallerModel->getById($solicitud['taller_id']);

        if (!$taller) {
            echo json_encode(['success' => false, 'error' => 'El taller no existe']);
            exit;
        }

        if ((int)$taller['cupo_disponible'] <= 0) {
            echo json_encode(['success' => false, 'error' => 'Sin cupos disponibles en este momento']);
            exit;
        }

        $descontado = $this->tallerModel->descontarCupo($solicitud['taller_id']);

        if (!$descontado) {
            echo json_encode(['success' => false, 'error' => 'No fue posible descontar el cupo']);
            exit;
        }

        $aprobada = $this->solicitudModel->aprobar($solicitudId);

        if (!$aprobada) {
            $this->tallerModel->sumarCupo($solicitud['taller_id']);
            echo json_encode(['success' => false, 'error' => 'No fue posible aprobar la solicitud']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Solicitud aprobada y cupo actualizado']);
        exit;
    }

    public function rechazar()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            exit;
        }

        $solicitudId = isset($_POST['id_solicitud']) ? (int)$_POST['id_solicitud'] : 0;

        if ($solicitudId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Solicitud inválida']);
            exit;
        }

        $solicitud = $this->solicitudModel->getById($solicitudId);

        if (!$solicitud) {
            echo json_encode(['success' => false, 'error' => 'La solicitud no existe']);
            exit;
        }

        if ($solicitud['estado'] !== 'pendiente') {
            echo json_encode(['success' => false, 'error' => 'La solicitud ya fue procesada']);
            exit;
        }

        $rechazada = $this->solicitudModel->rechazar($solicitudId);

        if ($rechazada) {
            echo json_encode(['success' => true, 'message' => 'Solicitud rechazada correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo rechazar la solicitud']);
        }
        exit;
    }
}
