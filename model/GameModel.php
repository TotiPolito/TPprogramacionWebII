<?php

class GameModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerPreguntaRandomGlobal($preguntasVistas = [])
    {
        $excluir = "";
        if (!empty($preguntasVistas)) {
            $ids = implode(',', $preguntasVistas);
            $excluir = "AND p.id NOT IN ($ids)";
        }

        $sql = "SELECT p.*, c.descripcion AS nombre_categoria 
                FROM preguntas p 
                JOIN categorias c ON p.categoria = c.id
                WHERE 1=1 $excluir
                ORDER BY RAND() 
                LIMIT 1";

        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_assoc();
    }

    public function obtenerRespuestas($preguntaId)
    {
        $sql = "SELECT * FROM respuestas WHERE idPregunta = $preguntaId";
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function verificarRespuesta($idRespuesta)
    {
        $sql = "SELECT estado FROM respuestas WHERE id = $idRespuesta";
        return $this->conexion->query($sql)->fetch_assoc();
    }

    public function obtenerPreguntaPorId($idPregunta)
    {
        $sql = "SELECT 
                p.*, 
                c.descripcion AS nombre_categoria,
                CASE 
                    WHEN p.vistas = 0 THEN 0
                    ELSE ROUND(p.aciertos / p.vistas, 2)
                END AS ratio,
                CASE
                    WHEN p.vistas = 0 THEN 'Sin datos'
                    WHEN (p.aciertos / p.vistas) >= 0.7 THEN 'Fácil'
                    WHEN (p.aciertos / p.vistas) >= 0.5 THEN 'Normal'
                    ELSE 'Difícil'
                END AS dificultad_actual
            FROM preguntas p
            JOIN categorias c ON p.categoria = c.id
            WHERE p.id = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado->fetch_assoc();
    }

    public function incrementarVistasPregunta($idPregunta)
    {
        $sql = "UPDATE preguntas SET vistas = vistas + 1 WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
    }

    public function incrementarAciertospregunta($idPregunta)
    {
        $sql = "UPDATE preguntas SET aciertos = aciertos + 1 WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
    }

    public function actualizarDificultad($idPregunta)
    {
        $sql = "UPDATE preguntas 
        SET dificultad = CASE
        WHEN vistas = 0 THEN 'Sin datos'
        WHEN (aciertos / vistas) >= 0.7 THEN 'Facil'
        WHEN (aciertos / vistas) >= 0.5 THEN 'Normal'
        ELSE 'Dificil'
        END WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
    }
}