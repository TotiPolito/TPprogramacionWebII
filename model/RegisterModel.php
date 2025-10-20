<?php
require_once("helper/MyConexion.php");

class RegisterModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = MyConexion::getInstance()->getConexion();
        $this->conexion->select_db("preguntados");
    }

    public function usuarioExiste($usuario)
    {
        $query = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function mailExiste($mail)
    {
        $query = "SELECT * FROM usuarios WHERE mail = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function crearUsuario($data)
    {
        $query = "INSERT INTO usuarios 
                  (nombre_completo, anio_nacimiento, sexo, pais, ciudad, mail, usuario, password, foto_perfil)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($query);

        $passwordHash = password_hash($data["password"], PASSWORD_BCRYPT);

        $stmt->bind_param(
            "sisssssss",
            $data["nombre_completo"],
            $data["anio_nacimiento"],
            $data["sexo"],
            $data["pais"],
            $data["ciudad"],
            $data["mail"],
            $data["usuario"],
            $passwordHash,
            $data["foto_perfil"]
        );
        $stmt->execute();
    }
}
?>
