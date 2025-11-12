<?php

class AdminModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerEstadisticasGenerales()
    {
        $db = $this->conexion->getConexion();

        $stats = [];

        $stats["total_usuarios"] = $db->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()["total"];
        $stats["total_jugadores"] = $db->query("SELECT COUNT(*) as total FROM usuarios WHERE rol='jugador'")->fetch_assoc()["total"];
        $stats["total_partidas"] = $db->query("SELECT COUNT(*) as total FROM partida p INNER JOIN usuarios u on p.id_usuario = u.id WHERE u.rol = 'jugador'")->fetch_assoc()["total"];
        $stats["promedio_puntaje"] = $db->query("SELECT AVG(puntaje) as promedio FROM usuarios WHERE rol='jugador'")->fetch_assoc()["promedio"];
        $stats["mejor_jugador"] = $db->query("SELECT usuario, puntaje FROM usuarios WHERE rol='jugador' ORDER BY puntaje DESC LIMIT 1")->fetch_assoc();

        return $stats;
    }

    public function obtenerEstadisticasFiltradas($filtros)
    {
        $db = $this->conexion->getConexion();

        $query = "SELECT * FROM partidas WHERE 1=1";
        $params = [];
        $types = "";

        if ($filtros["fecha_inicio"]) {
            $query .= " AND fecha >= ?";
            $params[] = $filtros["fecha_inicio"];
            $types .= "s";
        }

        if ($filtros["fecha_fin"]) {
            $query .= " AND fecha <= ?";
            $params[] = $filtros["fecha_fin"];
            $types .= "s";
        }

        if ($filtros["usuario"]) {
            $query .= " AND id_usuario = ?";
            $params[] = $filtros["usuario"];
            $types .= "i";
        }

        $stmt = $db->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
