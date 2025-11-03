<?php

class PartidasModel {

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function guardarPartida($idUsuario, $puntaje)
    {
        $sql = "INSERT INTO partida (id_usuario, puntaje, fecha) VALUES (?, ?, NOW())";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $idUsuario, $puntaje);
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerPartidasPorUsuario($idUsuario)
    {
        $sql = "SELECT puntaje, fecha 
                FROM partida 
                WHERE id_usuario = ? 
                ORDER BY fecha DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $partidas = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $partidas;
    }

    public function actualizarPuntajeUsuario($idUsuario, $puntaje)
    {
        $sql = "UPDATE usuarios SET puntaje = puntaje + ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $puntaje, $idUsuario);
        $stmt->execute();
        $stmt->close();
    }
}
