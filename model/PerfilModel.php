<?php

class PerfilModel {

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerPerfilPorId($idJugador)
    {
        $sql = "SELECT 
                    u.id,
                    u.nombre_completo, 
                    u.puntaje, 
                    COUNT(p.id) AS partidas, 
                    u.latitud, 
                    u.longitud,
                    u.qr AS qr
                FROM usuarios u
                LEFT JOIN partida p ON u.id = p.id_usuario
                WHERE u.id = ?
                GROUP BY u.id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idJugador);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
}
