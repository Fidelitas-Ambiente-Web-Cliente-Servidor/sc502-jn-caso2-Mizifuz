<?php
class Taller
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM talleres ORDER BY nombre ASC";
        $result = $this->conn->query($query);

        $talleres = [];
        while ($row = $result->fetch_assoc()) {
            $talleres[] = $row;
        }

        return $talleres;
    }

    public function getAllDisponibles()
    {
        $query = "SELECT *
                  FROM talleres
                  WHERE cupo_disponible > 0
                  ORDER BY nombre ASC";
        $result = $this->conn->query($query);

        $talleres = [];
        while ($row = $result->fetch_assoc()) {
            $talleres[] = $row;
        }

        return $talleres;
    }

    public function getDisponiblesConEstadoUsuario($usuarioId)
    {
        $query = "SELECT 
                    t.id,
                    t.nombre,
                    t.descripcion,
                    t.cupo_maximo,
                    t.cupo_disponible,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1
                            FROM solicitudes s
                            WHERE s.taller_id = t.id
                              AND s.usuario_id = ?
                              AND s.estado IN ('pendiente', 'aprobada')
                        ) THEN 1
                        ELSE 0
                    END AS ya_solicitado
                  FROM talleres t
                  WHERE t.cupo_disponible > 0
                  ORDER BY t.nombre ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        $talleres = [];
        while ($row = $result->fetch_assoc()) {
            $row['ya_solicitado'] = (int) $row['ya_solicitado'];
            $talleres[] = $row;
        }

        return $talleres;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM talleres WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function descontarCupo($tallerId)
    {
        $query = "UPDATE talleres
                  SET cupo_disponible = cupo_disponible - 1
                  WHERE id = ? AND cupo_disponible > 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $tallerId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function sumarCupo($tallerId)
    {
        $query = "UPDATE talleres
                  SET cupo_disponible = cupo_disponible + 1
                  WHERE id = ? AND cupo_disponible < cupo_maximo";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $tallerId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}
