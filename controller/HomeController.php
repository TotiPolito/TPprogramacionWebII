<?php

class HomeController
{
    private $renderer;
    private $model;

    public function __construct($renderer, $model)
    {
        $this->renderer = $renderer;
        $this->model = $model;
    }

    public function Game()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["usuario"])) {
            header("Location:/TPprogramacionWebII/index.php?controller=Login&method=mostrarLogin");
            exit;
        }

        $usuario = $_SESSION["usuario"];

        if ($usuario["rol"] !== "jugador") {
            switch ($usuario["rol"]) {
                case "editor":
                    header("Location:/TPprogramacionWebII/index.php?controller=Editor&method=panel");
                    exit;
                case "admin":
                    exit;
            }
        }
        $idUsuario = $usuario["id"];
        $usuarioActualizado = $this->model->obtenerUsuarioPorId($idUsuario);
        $_SESSION["usuario"] = $usuarioActualizado;

        $data = [
            "logueado" => true,
            "nombre_completo" => $usuarioActualizado["nombre_completo"],
            "puntaje" => $usuarioActualizado["puntaje"]
        ];

        $this->renderer->render("home", $data);
    }

}