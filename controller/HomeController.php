<?php

class HomeController
{
    private $renderer;

    public function __construct($renderer) {
        $this->renderer = $renderer;
    }

    public function Game()
    {
        $this->renderer->render("home");
    }
}