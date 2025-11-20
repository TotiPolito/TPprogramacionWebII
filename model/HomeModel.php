<?php

class HomeModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerUsuarioPorId($idUsuario)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        return $usuario;
    }

    public function editarPerfil($id, $usuario, $password, $fotoPerfil)
    {
        $sql = "UPDATE usuarios SET ";
        $paramTypes = "";
        $params = [];

        if (!empty($usuario)) {
            $sql .= "usuario=?, ";
            $paramTypes .= "s";
            $params[] = $usuario;
        }

        if (!empty($password)) {
            $sql .= "password=?, ";
            $paramTypes .= "s";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (!empty($fotoPerfil)) {
            $sql .= "foto_perfil=?, ";
            $paramTypes .= "s";
            $params[] = $fotoPerfil;
        }

        if (empty($params)) {
            return false;
        }

        $sql = rtrim($sql, ", ");

        $sql .= " WHERE id=?";
        $paramTypes .= "i";
        $params[] = $id;

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($paramTypes, ...$params);

        return $stmt->execute();
    }
}
