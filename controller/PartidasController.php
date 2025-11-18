<?php

class PartidasController
{
    private $renderer;
    private $model;

    public function __construct($renderer, $model)
    {
        $this->renderer = $renderer;
        $this->model = $model;
    }

    public function listarPartidas()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /TPprogramacionWebII/Login/loginForm");
            exit;
        }

        $usuario = $_SESSION['usuario'];
        $partidas = $this->model->obtenerPartidasPorUsuario($usuario['id']);

        $this->renderer->render("partidas", [
            "nombre_completo" => $usuario['nombre_completo'],
            "partidas" => $partidas
        ]);
    }
}