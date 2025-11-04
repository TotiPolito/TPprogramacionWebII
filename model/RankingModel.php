<?php

class RankingModel{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerRanking()
    {
        $sql = "SELECT u.id, u.usuario,
        ((u.puntaje) / COUNT(p.id)) AS promedio_puntaje
        FROM usuarios u 
        LEFT JOIN partida p ON u.id = p.id_usuario
        GROUP BY u.id
        ORDER BY promedio_puntaje DESC
        LIMIT 10;";
        return $this->conexion->query($sql);
    }
}
