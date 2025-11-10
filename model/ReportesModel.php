<?php

class ReportesModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerReportes() {
        $conn = $this->conexion->getConexion();
        $sql = "SELECT 
                    r.id AS idReporte, 
                    r.motivo, 
                    p.id AS idPregunta, 
                    p.descripcion AS pregunta, 
                    u.nombre_completo AS usuario
                FROM preguntas_reportadas r
                JOIN preguntas p ON r.idPregunta = p.id
                JOIN usuarios u ON r.idUsuario = u.id";

        $resultado = $conn->query($sql);

        if (!$resultado) {
            error_log("Error al obtener reportes: " . $conn->error);
            return [];
        }

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function aceptarReporte($idReporte) {
        $conn = $this->conexion->getConexion();

        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("SELECT idPregunta FROM preguntas_reportadas WHERE id = ?");
            $stmt->bind_param("i", $idReporte);
            $stmt->execute();
            $resultado = $stmt->get_result()->fetch_assoc();

            if (!$resultado) throw new Exception("No se encontró el reporte $idReporte");

            $idPregunta = $resultado['idPregunta'];

            //Borrar respuestas asociadas
            $stmtResp = $conn->prepare("DELETE FROM respuestas WHERE idPregunta = ?");
            $stmtResp->bind_param("i", $idPregunta);
            $stmtResp->execute();

            //Borrar la pregunta
            $stmtPreg = $conn->prepare("DELETE FROM preguntas WHERE id = ?");
            $stmtPreg->bind_param("i", $idPregunta);
            $stmtPreg->execute();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error al aceptar reporte: " . $e->getMessage());
        }
    }


    public function rechazarReporte($idReporte) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("DELETE FROM preguntas_reportadas WHERE id = ?");
        $stmt->bind_param("i", $idReporte);
        $stmt->execute();
    }
}