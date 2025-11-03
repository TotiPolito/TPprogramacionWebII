<?php

class RankingModel{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerRanking(){
        $sql = "SELECT DISTINCT id, usuario, puntaje 
                FROM usuarios
                ORDER BY puntaje DESC
                LIMIT 10";
                return $this->conexion->query($sql);
    }
}
