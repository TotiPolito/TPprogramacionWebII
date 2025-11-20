<?php

class PreguntaSugeridaModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function guardarPreguntaSugerida($texto, $categoria_id, $sugerida_por, $respuestas, $correcta, $imagen) {
        $conn = $this->conexion->getConexion();

        $stmt = $conn->prepare("INSERT INTO preguntas_sugeridas (texto, categoria_id, sugerida_por, imagen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siis", $texto, $categoria_id, $sugerida_por, $imagen);
        $stmt->execute();

        $idPregunta = $conn->insert_id;

        $stmtResp = $conn->prepare("INSERT INTO respuestas_preguntas_sugeridas (idPregunta, descripcion, estado) VALUES (?, ?, ?)");

        foreach ($respuestas as $i => $descripcion) {
            $estado = ($i + 1 == $correcta) ? 1 : 0;
            $stmtResp->bind_param("isi", $idPregunta, $descripcion, $estado);
            $stmtResp->execute();
        }

        return true;
    }


    public function obtenerCategorias() {
        $conn = $this->conexion->getConexion();
        $result = $conn->query("SELECT id, descripcion FROM categorias");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerSugerenciasPendientes() {
        $conn = $this->conexion->getConexion();

        $sql = "SELECT ps.id, ps.texto, ps.imagen, c.descripcion AS categoria, u.nombre_completo AS usuario
            FROM preguntas_sugeridas ps
            JOIN categorias c ON ps.categoria_id = c.id
            JOIN usuarios u ON ps.sugerida_por = u.id
            WHERE ps.aprobada IS NULL";

        $resultado = $conn->query($sql);
        $sugerencias = $resultado->fetch_all(MYSQLI_ASSOC);

        foreach ($sugerencias as $k => $s) {
            $stmt = $conn->prepare("SELECT descripcion, estado FROM respuestas_preguntas_sugeridas WHERE idPregunta = ?");
            $stmt->bind_param("i", $s['id']);
            $stmt->execute();
            $respuestas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $sugerencias[$k]['respuestas'] = $respuestas;
        }

        return $sugerencias;
    }

    public function obtenerRespuestas($idSugerida) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("SELECT descripcion, estado FROM respuestas_preguntas_sugeridas WHERE idPregunta = ?");
        $stmt->bind_param("i", $idSugerida);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function aprobarSugerencia($idSugerida) {
        $conn = $this->conexion->getConexion();
        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("
            SELECT texto, categoria_id, imagen 
            FROM preguntas_sugeridas 
            WHERE id = ?
        ");
            $stmt->bind_param("i", $idSugerida);
            $stmt->execute();
            $sugerencia = $stmt->get_result()->fetch_assoc();

            if (!$sugerencia) throw new Exception("Sugerencia no encontrada");

            $texto = $sugerencia['texto'];
            $categoria = $sugerencia['categoria_id'];
            $imagen = $sugerencia['imagen'];

            $stmtPreg = $conn->prepare("
            INSERT INTO preguntas (descripcion, categoria, imagen) 
            VALUES (?, ?, ?)
        ");
            $stmtPreg->bind_param("sis", $texto, $categoria, $imagen);
            $stmtPreg->execute();

            $idPreguntaNueva = $conn->insert_id;

            $respuestas = $this->obtenerRespuestas($idSugerida);
            $stmtResp = $conn->prepare("
            INSERT INTO respuestas (idPregunta, descripcion, estado) 
            VALUES (?, ?, ?)
        ");

            foreach ($respuestas as $resp) {
                $estado = $resp['estado'];
                $desc = $resp['descripcion'];
                $stmtResp->bind_param("isi", $idPreguntaNueva, $desc, $estado);
                $stmtResp->execute();
            }

            $stmtUpdate = $conn->prepare("
            UPDATE preguntas_sugeridas 
            SET aprobada = 1 
            WHERE id = ?
        ");
            $stmtUpdate->bind_param("i", $idSugerida);
            $stmtUpdate->execute();

            $conn->commit();

        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error al aprobar sugerencia: " . $e->getMessage());
            throw $e;
        }
    }

    public function rechazarSugerencia($idSugerida) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("UPDATE preguntas_sugeridas SET aprobada = 0 WHERE id = ?");
        $stmt->bind_param("i", $idSugerida);
        $stmt->execute();
    }
}