<?php

class LoginController
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
        $this->login();
    }

    public function loginForm()
    {
        $this->renderer->render("login");
    }

    public function login()
    {
        $usuarioIngresado = $_POST["usuario"] ?? "";
        $passwordIngresada = $_POST["password"] ?? "";

        $resultado = $this->model->getUserWith($usuarioIngresado);

        if ($resultado && password_verify($passwordIngresada, $resultado["password"])) {
            // Si coincide, iniciamos sesión
            $_SESSION["usuario"] = $resultado["usuario"];
            $this->home();
        } else {
            // Si no coincide, mostramos error
            $this->renderer->render("login", ["error" => "Usuario o clave incorrecta"]);
        }
    }

    public function home() {
        echo $this->renderer->render("home");
    }

    public function logout()
    {
        session_destroy();
        $this->redirectToIndex();
    }

    public function mostrarLogin()
    {
        echo $this->renderer->render("login", []);
    }

    public function redirectToIndex()
    {
        header("Location: /TPprogramacionWebII/index.php");
        exit;
    }
}