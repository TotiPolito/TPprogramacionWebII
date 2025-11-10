<?php

class EditorModel {
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function contarReportesPendientes() {
        $conn = $this->conexion->getConexion();
        $query = "SELECT COUNT(*) AS total FROM preguntas_reportadas";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        return $row["total"] ?? 0;
    }

    public function contarSugerenciasPendientes() {
        $conn = $this->conexion->getConexion();
        $query = "SELECT COUNT(*) AS total FROM preguntas_sugeridas WHERE aprobada IS NULL";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        return $row["total"] ?? 0;
    }
}