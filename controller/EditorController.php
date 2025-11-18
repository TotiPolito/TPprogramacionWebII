<?php

class EditorController {
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function panel()
    {
        if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== "editor") {
            header("Location: /TPprogramacionWebII/Login/loginForm");
            exit;
        }

        $data = [
            "usuario" => $_SESSION["usuario"],
            "cantidadReportes" => $this->model->contarReportesPendientes(),
            "cantidadSugerencias" => $this->model->contarSugerenciasPendientes()
        ];

        echo $this->renderer->render("panelEditor", $data);
    }
}