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


    public function iniciar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Reiniciamos datos de la partida
        $_SESSION['preguntas_vistas'] = [];
        $_SESSION['aciertos'] = 0;
        $_SESSION['num_preguntas'] = 0;

        // Permitimos entrar a jugar desde Home
        $_SESSION['permitir_siguiente'] = true;

        // Redirigimos al método jugar
        header("Location: /TPprogramacionWebII/index.php?controller=Game&method=jugar");
        exit;
    }
    public function jugar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Si no viene con permiso desde "resultado", lo mandamos al home
        if (empty($_SESSION['permitir_siguiente']) || $_SESSION['permitir_siguiente'] !== true) {
            header("Location: /TPprogramacionWebII/index.php?controller=Home&method=Game");
            exit;
        }

        // Consumimos el permiso para que no se pueda volver a usar
        unset($_SESSION['permitir_siguiente']);



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
        $this->model->incrementarVistasPregunta($pregunta['id']);
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
        $idPregunta = $_POST['idPregunta'];
        $resultado = $this->model->verificarRespuesta($idRespuesta);
        $respuestaCorrecta = $resultado['estado'] == 1;

        if (!isset($_SESSION['aciertos'])) $_SESSION['aciertos'] = 0;

        if($respuestaCorrecta) {
            $_SESSION['aciertos']++;
            $this->model->incrementarAciertosPregunta($idPregunta);
        }
        $this->model->actualizarDificultad($idPregunta);

        $_SESSION['bloquear_retroceso'] = false;

        if(!$respuestaCorrecta) {
            $totalAciertos = $_SESSION['aciertos'];

            if (isset($_SESSION['usuario']['id'])) {
                $idUsuario = $_SESSION['usuario']['id'];
                $this->partidasModel->guardarPartida($idUsuario, $totalAciertos);
                $this->partidasModel->actualizarPuntajeUsuario($idUsuario, $totalAciertos);
            }

            $_SESSION['ultima_pregunta_resuelta'] = true;

            $_SESSION['preguntas_vistas'] = [];
            $_SESSION['aciertos'] = 0;
            $_SESSION['num_preguntas'] = 0;

            header("Location: /TPprogramacionWebII/index.php?controller=Game&method=mostrarResultado&idPregunta=" . $idPregunta . "&correcta=false");
            echo $this->renderer->render("fin", [ 'aciertos' => $totalAciertos, 'mensaje' => '¡Perdiste!' ]);
            exit;
        }
        $_SESSION['ultima_pregunta_resuelta'] = true;
        header("Location: /TPprogramacionWebII/index.php?controller=Game&method=mostrarResultado&idPregunta=" . $idPregunta . "&correcta=true");
        exit;
        }

    public function mostrarResultado()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Permitir la carga de la siguiente pregunta solo desde la vista de resultado
        $_SESSION['permitir_siguiente'] = true;

        //Deshabilitamos completamente la caché del navegador
        header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

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