<?php

class LoginModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getUserWith($user)
    {
        // Consulta segura para evitar inyección SQL
        $sql = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();

        // Obtenemos el resultado
        $result = $stmt->get_result();

        // fetch_assoc convierte el resultado en un array asociativo
        return $result->fetch_assoc(); // Devuelve array o null si no hay usuario
    }
}