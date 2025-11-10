<?php

class ReportarModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function guardarReporte($idPregunta, $idUsuario, $motivo) {
        $conn = $this->conexion->getConexion();

        $stmt = $conn->prepare("
            INSERT INTO preguntas_reportadas (idPregunta, idUsuario, motivo)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("iis", $idPregunta, $idUsuario, $motivo);
        $stmt->execute();
    }

    public function obtenerPreguntaPorId($idPregunta) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("SELECT * FROM preguntas WHERE id = ?");
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
}