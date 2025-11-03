<?php

class PerfilModel {

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerPerfilPorId($idJugador)
    {
        $sql = "SELECT u.nombre_completo, u.puntaje, COUNT(p.id) AS partidas, u.latitud, u.longitud
                FROM usuarios u
                LEFT JOIN partida p ON u.id = p.id_usuario
                WHERE u.id = $idJugador
                GROUP BY u.id";

        $resultado = $this->conexion->query($sql);
        return $resultado ? $resultado->fetch_assoc() : null;
    }
}
