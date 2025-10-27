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
        $resultado = $this->model->getUserWith($_POST["usuario"], $_POST["password"]);

        if ($resultado) {
            $_SESSION["usuario"] = $resultado["usuario"];
            $this->home();
        } else {
            $this->renderer->render("login", ["error" => "Usuario o contraseña incorrecta"]);
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
        header("Location: /Preguntados/index.php");
        exit;
    }
}