<?php

class PreguntaSugeridaController {
    private $model;
    private $renderer;

    public function __construct($model, $renderer) {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    // Muestra el formulario para sugerir una pregunta
    public function sugerirForm() {
        $categorias = $this->model->obtenerCategorias();

        // Renderizamos la vista con la lista de categorías
        $this->renderer->render("sugerirPregunta", ["categorias" => $categorias]);
    }

    // Procesa el formulario al enviarse
    public function guardar() {
        if (!isset($_SESSION["usuario"])) {
            header("Location: /TPprogramacionWebII/index.php?controller=Login&method=loginForm");
            exit;
        }

        $texto = $_POST["pregunta"];

        $categoria_id = $_POST["categoria"];

        $sugerida_por = $_SESSION["usuario"]["id"];

        $respuestas = [
            $_POST["respuesta1"],
            $_POST["respuesta2"],
            $_POST["respuesta3"],
            $_POST["respuesta4"]
        ];
        $correcta = $_POST["correcta"];

        $this->model->guardarPreguntaSugerida($texto, $categoria_id, $sugerida_por, $respuestas, $correcta);


        $categorias = $this->model->obtenerCategorias();
        $this->renderer->render("sugerirPregunta", [ "mensaje" => "¡Tu pregunta fue enviada para revisión!", "categorias" => $categorias]);
    }

    // Listar las sugerencias pendientes para el editor
    public function listarPendientes() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'editor') {
            header("Location: /TPprogramacionWebII/index.php?controller=Login&method=loginForm");
            exit;
        }

        $sugerencias = $this->model->obtenerSugerenciasPendientes();
        $this->renderer->render("sugerenciasPendientes", ["sugerencias" => $sugerencias]);
    }

    // Aprobar una sugerencia
    public function aprobar() {
        $id = $_GET['idSugerida'];
        $this->model->aprobarSugerencia($id);

        header("Location: /TPprogramacionWebII/index.php?controller=PreguntaSugerida&method=listarPendientes");
        exit;
    }

    // Rechazar una sugerencia
    public function rechazar() {
        $id = $_GET['idSugerida'];
        $this->model->rechazarSugerencia($id);

        header("Location: /TPprogramacionWebII/index.php?controller=PreguntaSugerida&method=listarPendientes");
        exit;
    }
}