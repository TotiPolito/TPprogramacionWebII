<?php

class GameController
{
    private $renderer;
    private $model;

    public function __construct($renderer, $model) {
        $this->renderer = $renderer;
        $this->model = $model;
    }

    public function jugar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Inicializar variables de sesión
        if (!isset($_SESSION['preguntas_vistas'])) $_SESSION['preguntas_vistas'] = [];
        if (!isset($_SESSION['aciertos'])) $_SESSION['aciertos'] = 0;
        if (!isset($_SESSION['num_preguntas'])) $_SESSION['num_preguntas'] = 0;

        // Obtener una pregunta nueva (excluyendo las ya vistas)
        $pregunta = $this->model->obtenerPreguntaRandomGlobal($_SESSION['preguntas_vistas']);

        if (!$pregunta) {
            // Si no quedan preguntas, terminamos el juego
            $totalAciertos = $_SESSION['aciertos'];
            $_SESSION['preguntas_vistas'] = [];
            $_SESSION['aciertos'] = 0;
            $_SESSION['num_preguntas'] = 0;

            echo $this->renderer->render("fin", [
                'aciertos' => $totalAciertos
            ]);
            exit;
        }

        // Guardar que ya se mostró esta pregunta
        $_SESSION['preguntas_vistas'][] = $pregunta['id'];

        // Obtener respuestas de la pregunta
        $respuestas = $this->model->obtenerRespuestas($pregunta['id']);

        // Renderizar la vista del juego
        echo $this->renderer->render("game", [
            'categoria' => $pregunta['nombre_categoria'],
            'pregunta' => $pregunta,
            'respuestas' => $respuestas
        ]);
    }

    public function responder()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idRespuesta = $_POST['idRespuesta'];

        $resultado = $this->model->verificarRespuesta($idRespuesta);

        // Inicializar variables si no existen
        if (!isset($_SESSION['aciertos'])) $_SESSION['aciertos'] = 0;
        if (!isset($_SESSION['num_preguntas'])) $_SESSION['num_preguntas'] = 0;

        // Contabilizar acierto
        if ($resultado['estado'] == 1) $_SESSION['aciertos']++;

        $_SESSION['num_preguntas']++;

        // Si completamos 10 preguntas, terminar juego
        if ($_SESSION['num_preguntas'] >= 10) {
            $totalAciertos = $_SESSION['aciertos'];

            // Reiniciar juego
            $_SESSION['preguntas_vistas'] = [];
            $_SESSION['aciertos'] = 0;
            $_SESSION['num_preguntas'] = 0;

            echo $this->renderer->render("fin", [
                'aciertos' => $totalAciertos
            ]);
            exit;
        }

        // Si aún no completamos 10 preguntas, pasar a la siguiente
        header("Location: /Preguntados/index.php?controller=Game&method=jugar");
        exit;
    }
}