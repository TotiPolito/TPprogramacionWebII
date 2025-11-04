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
        $sql = "SELECT p.*, c.descripcion AS nombre_categoria 
                FROM preguntas p 
                JOIN categorias c ON p.categoria = c.id
                WHERE p.id = $idPregunta";

        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_assoc();
    }
}