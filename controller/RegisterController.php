<?php
require_once("model/RegisterModel.php");

class RegisterController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function base()
    {
        $this->registerForm();
    }

    public function registerForm()
    {
        $this->renderer->render("register");
    }

    public function register()
    {
        $data = [
            "nombre_completo" => $_POST["nombre_completo"] ?? "",
            "anio_nacimiento" => $_POST["anio_nacimiento"] ?? "",
            "sexo" => $_POST["sexo"] ?? "",
            "pais" => $_POST["pais"] ?? "",
            "ciudad" => $_POST["ciudad"] ?? "",
            "mail" => $_POST["mail"] ?? "",
            "usuario" => $_POST["usuario"] ?? "",
            "password" => $_POST["password"] ?? "",
            "repassword" => $_POST["repassword"] ?? "",
            "foto_perfil" => $_FILES["foto_perfil"]["name"] ?? null
        ];

        if (in_array("", [$data["nombre_completo"], $data["anio_nacimiento"], $data["sexo"], $data["pais"], $data["ciudad"], $data["mail"], $data["usuario"], $data["password"], $data["repassword"]])) {
            $this->renderer->render("register", ["error" => "Todos los campos son obligatorios"]);
            return;
        }

        if ($data["password"] !== $data["repassword"]) {
            $this->renderer->render("register", ["error" => "Las contraseñas no coinciden"]);
            return;
        }

        if ($this->model->usuarioExiste($data["usuario"])) {
            $this->renderer->render("register", ["error" => "El nombre de usuario ya existe"]);
            return;
        }

        if ($this->model->mailExiste($data["mail"])) {
            $this->renderer->render("register", ["error" => "El mail ya está registrado"]);
            return;
        }

        if ($data["foto_perfil"]) {
            $rutaDestino = "imagenes/" . basename($data["foto_perfil"]);
            move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $rutaDestino);
            $data["foto_perfil"] = $rutaDestino;
        }

        $this->model->crearUsuario($data);

        header("Location: index.php?controller=Login&method=mostrarLogin");
        exit;
    }
}

?>
