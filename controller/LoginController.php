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
            $_SESSION["usuario"] = $resultado;
            $this->home();
        } else {
            $this->renderer->render("login", ["error" => "Usuario no validado o contraseña incorrecta"]);
        }
    }


    public function home() {
        header("Location: /TPprogramacionWebII/index.php?controller=Home&method=Game");
        exit;
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