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

        $_SESSION['preguntas_vistas'] = [];
        $_SESSION['aciertos'] = 0;
        $_SESSION['num_preguntas'] = 0;

        $_SESSION['permitir_siguiente'] = true;

        header("Location: /TPprogramacionWebII/Game/jugar");
        exit;
    }
    public function jugar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['permitir_siguiente']) || $_SESSION['permitir_siguiente'] !== true) {
            header("Location: /TPprogramacionWebII/Home/Game");
            exit;
        }

        unset($_SESSION['permitir_siguiente']);

        if (!isset($_SESSION['preguntas_vistas'])) $_SESSION['preguntas_vistas'] = [];
        if (!isset($_SESSION['aciertos'])) $_SESSION['aciertos'] = 0;
        if (!isset($_SESSION['num_preguntas'])) $_SESSION['num_preguntas'] = 0;

        $idUsuario = $_SESSION['usuario']['id'] ?? null;

        $pregunta = $this->model->obtenerPreguntaPorDificultad($idUsuario, $_SESSION['preguntas_vistas']);

        if (!$pregunta) {
            $_SESSION['preguntas_vistas'] = [];
            $pregunta = $this->model->obtenerPreguntaPorDificultad($idUsuario, []);
        }

        $nombreCategoria = $pregunta['nombre_categoria'] ?? 'Sin categoría';
        $colorFondo = $this->ObtenerColorPorCategoria($nombreCategoria);

        $_SESSION['preguntas_vistas'][] = $pregunta['id'];
        $this->model->incrementarVistasPregunta($pregunta['id']);
        $respuestas = $this->model->obtenerRespuestas($pregunta['id']);

        echo $this->renderer->render("game", [
            'categoria' => $pregunta['nombre_categoria'],
            'pregunta' => $pregunta,
            'respuestas' => $respuestas,
            'color' => $colorFondo
        ]);
    }

    private function obtenerColorPorCategoria($categoria)
    {
        $colores = [
            'deportes' => '#ef9222',
            'historia' => '#fdd730',
            'arte' => '#f63232',
            'ciencia' => '#4dfd3a',
            'geografia' => '#3498db',
            'entretenimiento' => '#ed479d'
        ];

        return $colores[$categoria] ?? '#ffffff';
    }

    public function responder() {

            if (session_status() === PHP_SESSION_NONE) session_start();

            $idRespuesta = $_POST['idRespuesta'];
            $idPregunta = $_POST['idPregunta'];
            $timeout = isset($_POST['timeout']) && $_POST['timeout'] === 'true';
            $tiempoInicio = $_POST['tiempoInicio'] ?? null;
            $tiempoActual = round(microtime(true) * 1000); // en milisegundos

            $fueraDeTiempo = false;

            if ($tiempoInicio) {
                $diferenciaSegundos = ($tiempoActual - $tiempoInicio) / 1000;
                if ($diferenciaSegundos > 10) { // límite de 10 segundos
                    $fueraDeTiempo = true;
                }
            }

        if ($timeout || $fueraDeTiempo) {
            $this->registrarPartidaIncorrecta($idPregunta);
            header("Location: /TPprogramacionWebII/Game/mostrarResultado&idPregunta=$idPregunta&correcta=false&timeout=true");
            exit;
        }

        $idRespuesta = $_POST['idRespuesta'];
        $idPregunta = $_POST['idPregunta'];
        $resultado = $this->model->verificarRespuesta($idRespuesta);
        $respuestaCorrecta = $resultado['estado'] == 1;

        if (!isset($_SESSION['aciertos'])) $_SESSION['aciertos'] = 0;

        $idUsuario = $_SESSION['usuario']['id'] ?? null;

        if ($idUsuario) {
            $this->model->incrementarVistasJugador($idUsuario);
        }

        if($respuestaCorrecta) {
            $_SESSION['aciertos']++;
            $this->model->incrementarAciertosPregunta($idPregunta);
            if ($idUsuario) {
                $this->model->incrementarAciertosJugador($idUsuario);
            }
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

            header("Location: /TPprogramacionWebII/Game/mostrarResultado&idPregunta=" . $idPregunta . "&correcta=false");
            exit;
        }
        $_SESSION['ultima_pregunta_resuelta'] = true;
        header("Location: /TPprogramacionWebII/Game/mostrarResultado&idPregunta=" . $idPregunta . "&correcta=true");
        exit;
        }

    public function mostrarResultado() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION['permitir_siguiente'] = true;

        header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $mensaje = null;
        if (isset($_GET['timeout']) && $_GET['timeout'] == 'true') {
            $mensaje = "¡Se acabó el tiempo!";
        }

        $idPregunta = $_GET['idPregunta'];
        $respuestaCorrecta = $_GET['correcta'] == 'true';

        $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);
        $respuestas = $this->model->obtenerRespuestas($idPregunta);

        $idUsuario = $_SESSION['usuario']['id'] ?? null;
        $ratioJugador = 0;
        $nivelJugador = 'Sin datos';

        if ($idUsuario) {
            $ratioJugador = $this->model->obtenerRatioJugador($idUsuario);

            $ratioJugador = floatval($ratioJugador);

            if ($ratioJugador >= 0.7) {
                $nivelJugador = 'Alto';
            } elseif ($ratioJugador >= 0.5) {
                $nivelJugador = 'Medio';
            } else {
                $nivelJugador = 'Bajo';
            }
        }

        echo $this->renderer->render("resultado", [
            'pregunta' => $pregunta,
            'respuestas' => $respuestas,
            'respuestaCorrecta' => $respuestaCorrecta,
            'ratioJugador' => number_format($ratioJugador, 2),
            'nivelJugador' => $nivelJugador,
            'aciertos' => $_SESSION['aciertos'] ?? 0,
            'mensaje' => $mensaje
        ]);
    }

    private function registrarPartidaIncorrecta($idPregunta) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $totalAciertos = $_SESSION['aciertos'] ?? 0;
        $idUsuario = $_SESSION['usuario']['id'] ?? null;

        if ($idUsuario) {
            $this->partidasModel->guardarPartida($idUsuario, $totalAciertos);
            $this->partidasModel->actualizarPuntajeUsuario($idUsuario, $totalAciertos);
        }

        $_SESSION['preguntas_vistas'] = [];
        $_SESSION['aciertos'] = 0;
        $_SESSION['num_preguntas'] = 0;
    }

}