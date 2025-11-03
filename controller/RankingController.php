<?php

class RankingController {

    private $renderer;
    private $model;

    public function __construct($renderer, $model)
    {
        $this->renderer = $renderer;
        $this->model = $model;
    }

    public function mostrar()
    {
        $resultado = $this->model->obtenerRanking();
        $ranking = [];
        $pos = 1;
        while ($fila = $resultado->fetch_assoc()) {
            $fila['posicion'] = $pos++;
            $ranking[] = $fila;
        }
        $data["ranking"] = $ranking;
        $this->renderer->render("ranking", $data);
    }
}
