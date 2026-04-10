<?php
class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function crear($tallerId, $usuarioId)
    {
        $query = "INSERT INTO solicitudes (taller_id, usuario_id, estado)
                  VALUES (?, ?, 'pendiente')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function existeSolicitudActiva($tallerId, $usuarioId)
    {
        $query = "SELECT id
                  FROM solicitudes
                  WHERE taller_id = ?
                    AND usuario_id = ?
                    AND estado IN ('pendiente', 'aprobada')
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM solicitudes WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getPendientes()
{
    $query = "SELECT 
                s.id AS id,
                s.usuario_id AS usuario_id,
                s.taller_id AS taller_id,
                s.fecha_solicitud AS fecha_solicitud,
                s.estado AS estado,
                t.nombre AS taller_nombre,
                u.username AS username
              FROM solicitudes s
              INNER JOIN talleres t ON s.taller_id = t.id
              INNER JOIN usuarios u ON s.usuario_id = u.id
              WHERE s.estado = 'pendiente'
              ORDER BY s.fecha_solicitud ASC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $solicitudes = [];
    while ($row = $result->fetch_assoc()) {
        $solicitudes[] = $row;
    }

    return $solicitudes;
}

    public function aprobar($id)
    {
        $query = "UPDATE solicitudes
                  SET estado = 'aprobada'
                  WHERE id = ? AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function rechazar($id)
    {
        $query = "UPDATE solicitudes
                  SET estado = 'rechazada'
                  WHERE id = ? AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}