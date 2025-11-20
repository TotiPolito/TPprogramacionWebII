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

    public function getCategorias() {
        $conn = $this->conexion->getConexion();
        $query = "SELECT * FROM categorias ORDER BY descripcion";
        return $conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function guardarCategoria($descripcion) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("INSERT INTO categorias(descripcion) VALUES (?)");
        $stmt->bind_param("s", $descripcion);
        $stmt->execute();
    }

    public function guardarPregunta($data)
    {
        $conn = $this->conexion->getConexion();

        $stmt = $conn->prepare("INSERT INTO preguntas(imagen, categoria, dificultad, descripcion) VALUES (?,?,?,?)");

        $stmt->bind_param("siss",
            $data["imagen"],
            $data["categoria"],
            $data["dificultad"],
            $data["descripcion"]
        );

        $stmt->execute();

        $idPregunta = $conn->insert_id;

        $respuestas = [
            $data["resp1"],
            $data["resp2"],
            $data["resp3"],
            $data["resp4"]
        ];

        foreach ($respuestas as $index => $texto) {
            if (!empty($texto)) {
                $estado = ($data["correcta"] == $index) ? 1 : 0;

                $stmt = $conn->prepare("
                INSERT INTO respuestas(idPregunta, descripcion, estado) 
                VALUES (?, ?, ?)
            ");
                $stmt->bind_param("isi", $idPregunta, $texto, $estado);
                $stmt->execute();
            }
        }
    }
}