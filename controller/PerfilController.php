<?php

class PerfilController {

    private $renderer;
    private $model;

    public function __construct($renderer, $model)
    {
        $this->renderer = $renderer;
        $this->model = $model;
    }

    public function mostrarPerfil()
    {
        if (!isset($_GET['id'])) {
            echo "ID de jugador no especificado";
            return;
        }

        $idJugador = $_GET['id'];
        $perfil = $this->model->obtenerPerfilPorId($idJugador);

        if ($perfil) {
            $this->renderer->render("perfil", ["perfil" => $perfil]);
        } else {
            echo "Jugador no encontrado";
        }
    }
}
