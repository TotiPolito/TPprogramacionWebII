<?php

class RegisterModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function usuarioExiste($usuario)
    {
        $query = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function mailExiste($mail)
    {
        $query = "SELECT * FROM usuarios WHERE mail = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function crearUsuario($data)
    {
        $query = "INSERT INTO usuarios 
                  (nombre_completo, anio_nacimiento, sexo, pais, ciudad, mail, usuario, password, foto_perfil)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->database->prepare($query);
        $stmt->bind_param(
            "sisssssss",
            $data["nombre_completo"],
            $data["anio_nacimiento"],
            $data["sexo"],
            $data["pais"],
            $data["ciudad"],
            $data["mail"],
            $data["usuario"],
            $data["password"],
            $data["foto_perfil"]
        );
        $stmt->execute();
    }
}
