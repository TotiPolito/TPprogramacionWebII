<?php

class HomeController
{
    private $renderer;
    private $homeModel;

    public function __construct($renderer, $homeModel) {
        $this->renderer = $renderer;
        $this->homeModel = $homeModel;
    }

    public function Game()
    {
        if(!isset($_SESSION["usuario"])) {
            header("Location:/TPprogramacionWebII/index.php?controller=Login&method=mostrarLogin");
            exit;
    }

        $idUsuario = $_SESSION["usuario"]["id"];

        $usuarioActualizado = $this->homeModel->obtenerUsuarioPorId($idUsuario);

        $_SESSION["usuario"] = $usuarioActualizado;

        $data = [
            "logueado" => true,
            "nombre_completo" => $usuarioActualizado["nombre_completo"],
            "puntaje" => $usuarioActualizado["puntaje"]
        ];

        $this->renderer->render("home", $data);
    }
}