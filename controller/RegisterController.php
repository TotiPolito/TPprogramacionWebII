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
        $this->renderer->render("register", ["noLink" => true]);
    }

    public function register()
    {
        $data = [
            "nombre_completo" => $_POST["nombre_completo"] ?? "",
            "anio_nacimiento" => $_POST["anio_nacimiento"] ?? "",
            "sexo" => $_POST["sexo"] ?? "",
            "pais" => $_POST["pais"] ?? "",
            "ciudad" => $_POST["ciudad"] ?? "",
            "latitud" => $_POST["latitud"] ?? null,
            "longitud" => $_POST["longitud"] ?? null,
            "mail" => $_POST["mail"] ?? "",
            "usuario" => $_POST["usuario"] ?? "",
            "password" => $_POST["password"] ?? "",
            "repassword" => $_POST["repassword"] ?? "",
            "foto_perfil" => $_FILES["foto_perfil"]["name"] ?? null
        ];

        if (in_array("", [
            $data["nombre_completo"], $data["anio_nacimiento"], $data["sexo"],
            $data["pais"], $data["ciudad"], $data["mail"],
            $data["usuario"], $data["password"], $data["repassword"]
        ])) {
            $this->renderer->render("register", ["error" => "Todos los campos son obligatorios"]);
            return;
        }


        if (!is_numeric($data["anio_nacimiento"]) || $data["anio_nacimiento"] <= 0) {
            $this->renderer->render("register", ["error" => "El año de nacimiento no puede ser negativo"]);
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
            $rutaDestino = "public/imagenes/" . basename($data["foto_perfil"]);
            move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $rutaDestino);
            $data["foto_perfil"] = $rutaDestino;
        }

        $registroExitoso = $this->model->crearUsuario($data);

        if ($registroExitoso) {
            $_SESSION['mensaje'] = "Se ha enviado un correo para confirmar su cuenta";
        } else {
            $_SESSION['mensaje'] = "Hubo un error al registrarse";
        }

        header("Location: index.php?controller=Register&method=mostrarRegister");
        exit;
    }

    public function mostrarRegister()
    {
        $mensaje = $_SESSION['mensaje'] ?? null;

        unset($_SESSION['mensaje']);

        $this->renderer->render("register", ["mensaje" => $mensaje]);
    }

    public function verificarMailAjax()
    {
        // Asegura que la respuesta sea JSON
        header('Content-Type: application/json');

        // Obtenemos el mail enviado por AJAX
        $mail = $_POST['mail'] ?? '';

        if (empty($mail)) {
            echo json_encode(['existe' => false, 'error' => 'No se recibió el mail']);
            return;
        }

        // Consultamos si existe en la base
        $existe = $this->model->mailExiste($mail);

        // Respondemos con JSON
        echo json_encode(['existe' => $existe]);
    }
}
?>
