<?php

class GameController
{
    private $renderer;
    private $model;
    private $partidasModel;

    public function __construct($renderer, $model, $partidasModel) {
        $this->renderer = $renderer;
        $this->model = $model;
        $this->partidasModel = $partidasModel;
    }

    public function jugar()
    {
        if (!isset($_SESSION['preguntas_vistas'])) $_SESSION['preguntas_vistas'] = [];
        if (!isset($_SESSION['aciertos'])) $_SESSION['aciertos'] = 0;
        if (!isset($_SESSION['num_preguntas'])) $_SESSION['num_preguntas'] = 0;

        $pregunta = $this->model->obtenerPreguntaRandomGlobal($_SESSION['preguntas_vistas']);

        if (!$pregunta) {
            $totalAciertos = $_SESSION['aciertos'];
            $_SESSION['preguntas_vistas'] = [];
            $_SESSION['aciertos'] = 0;
            $_SESSION['num_preguntas'] = 0;

            echo $this->renderer->render("fin", [
                'aciertos' => $totalAciertos
            ]);
            exit;
        }

        $_SESSION['preguntas_vistas'][] = $pregunta['id'];

        $respuestas = $this->model->obtenerRespuestas($pregunta['id']);

        echo $this->renderer->render("game", [
            'categoria' => $pregunta['nombre_categoria'],
            'pregunta' => $pregunta,
            'respuestas' => $respuestas
        ]);
    }

    public function responder()
    {
        $idRespuesta = $_POST['idRespuesta'];
        $resultado = $this->model->verificarRespuesta($idRespuesta);
        $respuestaCorrecta = $resultado['estado'] == 1;

        if (!isset($_SESSION['aciertos'])) $_SESSION['aciertos'] = 0;

        if($respuestaCorrecta) {
            $_SESSION['aciertos']++;
        }

        if(!$respuestaCorrecta) {
            $totalAciertos = $_SESSION['aciertos'];

            if (isset($_SESSION['usuario']['id'])) {
                $idUsuario = $_SESSION['usuario']['id'];
                $this->partidasModel->guardarPartida($idUsuario, $totalAciertos);
                $this->partidasModel->actualizarPuntajeUsuario($idUsuario, $totalAciertos);
            }

            $_SESSION['preguntas_vistas'] = [];
            $_SESSION['aciertos'] = 0;
            $_SESSION['num_preguntas'] = 0;

            header("Location: /TPprogramacionWebII/index.php?controller=Game&method=mostrarResultado&idPregunta=" . $_POST['idPregunta'] . "&correcta=" . ($respuestaCorrecta ? "true" : "false"));
            echo $this->renderer->render("fin", [ 'aciertos' => $totalAciertos, 'mensaje' => '¡Perdiste!' ]);
            exit;
        }
        header("Location: /TPprogramacionWebII/index.php?controller=Game&method=mostrarResultado&idPregunta=" . $_POST['idPregunta'] . "&correcta=" . ($respuestaCorrecta ? "true" : "false"));
        exit;
        }

    public function mostrarResultado()
    {
        $idPregunta = $_GET['idPregunta'];
        $respuestaCorrecta = $_GET['correcta'] == 'true';

        $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);
        $respuestas = $this->model->obtenerRespuestas($idPregunta);

        echo $this->renderer->render("resultado", [
            'pregunta' => $pregunta,
            'respuestas' => $respuestas,
            'respuestaCorrecta' => $respuestaCorrecta
        ]);
    }
}