<?php

class IndexController
{
    private $renderer;

    public function __construct($renderer)
    {
        $this->renderer = $renderer;
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