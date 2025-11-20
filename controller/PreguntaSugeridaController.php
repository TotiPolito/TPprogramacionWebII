<?php

class PreguntaSugeridaController {
    private $model;
    private $renderer;

    public function __construct($model, $renderer) {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function sugerirForm() {
        $categorias = $this->model->obtenerCategorias();

        // Renderizamos la vista con la lista de categorías
        $this->renderer->render("sugerirPregunta", ["categorias" => $categorias]);
    }

    public function guardar() {
        if (!isset($_SESSION["usuario"])) {
            header("Location: /TPprogramacionWebII/Login/loginForm");
            exit;
        }

        $texto = $_POST["pregunta"];
        $categoria_id = $_POST["categoria"];
        $sugerida_por = $_SESSION["usuario"]["id"];
        $imagenNombre = null;

        if (!empty($_FILES["imagen"]["name"])) {
            $ruta = "public/imagenes_sugeridas/";
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }

            $imagenNombre = time() . "_" . basename($_FILES["imagen"]["name"]);
            $rutaCompleta = $ruta . $imagenNombre;

            move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaCompleta);
        }

        $respuestas = [
            $_POST["respuesta1"],
            $_POST["respuesta2"],
            $_POST["respuesta3"],
            $_POST["respuesta4"]
        ];
        $correcta = $_POST["correcta"];

        $this->model->guardarPreguntaSugerida($texto, $categoria_id, $sugerida_por, $respuestas, $correcta, S);

        $categorias = $this->model->obtenerCategorias();
        $this->renderer->render("sugerirPregunta", [
            "mensaje" => "¡Tu pregunta fue enviada para revisión!",
            "categorias" => $categorias
        ]);
    }


    public function listarPendientes() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'editor') {
            header("Location: /TPprogramacionWebII/Login/loginForm");
            exit;
        }

        $sugerencias = $this->model->obtenerSugerenciasPendientes();
        $this->renderer->render("sugerenciasPendientes", ["sugerencias" => $sugerencias]);
    }

    public function aprobar() {
        $id = $_GET['idSugerida'];
        $this->model->aprobarSugerencia($id);

        header("Location: /TPprogramacionWebII/PreguntaSugerida/listarPendientes");
        exit;
    }

    public function rechazar() {
        $id = $_GET['idSugerida'];
        $this->model->rechazarSugerencia($id);

        header("Location: /TPprogramacionWebII/PreguntaSugerida/listarPendientes");
        exit;
    }
}