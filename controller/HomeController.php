<?php

class HomeController
{
    private $renderer;

    public function __construct($renderer) {
        $this->renderer = $renderer;
    }

    public function Game()
    {
        $data = [
            "logueado" => isset($_SESSION["usuario"])
        ];
        $this->renderer->render("home", $data);
    }
}