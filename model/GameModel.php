<?php

class GameModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerPreguntaPorDificultad($idUsuario, $preguntasVistas)
    {
        $ratioJugador = $this->obtenerRatioJugador($idUsuario);

        if ($ratioJugador === null) {
            $ratioJugador = 0.5;
        }

        if ($ratioJugador >= 0.7) {
            $condicion = "(p.aciertos / NULLIF(p.vistas, 0) <= 0.5 OR p.vistas = 0)";
        } elseif ($ratioJugador >= 0.5) {
            $condicion = "(p.aciertos / NULLIF(p.vistas, 0) BETWEEN 0.5 AND 0.7 OR p.vistas = 0)";
        } else {
            $condicion = "(p.aciertos / NULLIF(p.vistas, 0) >= 0.7 OR p.vistas = 0)";
        }

        $idsVistos = implode(',', $preguntasVistas ?: [0]);

        $sql = "SELECT p.*, c.descripcion AS nombre_categoria
            FROM preguntas p
            JOIN categorias c ON p.categoria = c.id
            WHERE p.id NOT IN ($idsVistos)
              AND $condicion
            ORDER BY RAND()
            LIMIT 1";

        $resultado = $this->conexion->query($sql);
        $pregunta = $resultado->fetch_assoc();

        return $pregunta;
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

    public function incrementarAciertosPregunta($idPregunta)
    {
        $sql = "UPDATE preguntas SET aciertos = aciertos + 1 WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
    }

    public function incrementarVistasJugador($idUsuario)
    {
        if ($idUsuario) {
            $sql = "UPDATE estadisticas_jugador SET preguntas_vistas = preguntas_vistas + 1 WHERE id_usuario = $idUsuario";
            $this->conexion->query($sql);
        }
    }

    public function incrementarAciertosJugador($idUsuario)
    {
        $sql = "UPDATE estadisticas_jugador SET aciertos = aciertos + 1 WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
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

    public function obtenerRatioJugador($idUsuario)
    {
        $sql = "SELECT 
                CASE 
                    WHEN preguntas_vistas = 0 THEN 0
                    ELSE ROUND(aciertos / preguntas_vistas, 2)
                END AS ratio
            FROM estadisticas_jugador
            WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila ? $fila['ratio'] : null;
    }
}