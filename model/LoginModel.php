<?php

class LoginModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getUserWith($user, $password)
    {
        $stmt = $this->conexion->getConexion()->prepare("SELECT * FROM usuarios WHERE usuario = ? AND validado = 1");
        $stmt->bind_param("s", $user);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            if (password_verify($password, $userData['password'])) {
                return $userData;
            }
        }

        return null;
    }

}