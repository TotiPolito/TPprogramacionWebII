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
            $fila['usuario'] = $fila['usuario'] ?? 'Desconocido';
            $fila['promedio_puntaje'] = $fila['promedio_puntaje'] ?? 0;
            $fila['id'] = $fila['id'] ?? 0;
            $ranking[] = $fila;
        }
        $data["ranking"] = $ranking;
        $this->renderer->render("ranking", $data);
    }
}
