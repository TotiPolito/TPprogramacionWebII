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

    public function agregarPreguntaForm()
    {
        $categorias = $this->model->getCategorias();
        echo $this->renderer->render("agregarPregunta", ["categorias" => $categorias]);
    }

    public function agregarPregunta()
    {
        // Procesar imagen
        $rutaImagen = null;

        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {

            $origen = $_FILES["imagen"]["tmp_name"];

            $nombre = uniqid() . "_" . basename($_FILES["imagen"]["name"]);

            $destino = __DIR__ . "/../../public/imagenesPreguntas/" . $nombre;

            move_uploaded_file($origen, $destino);

            $rutaImagen = "public/imagenesPreguntas/" . $nombre;
        }

        $_POST["imagen"] = $rutaImagen;

        $this->model->guardarPregunta($_POST);

        header("Location: /TPprogramacionWebII/Editor/panel");
    }


    public function agregarCategoriaForm()
    {
        echo $this->renderer->render("agregarCategoria");
    }

    public function agregarCategoria()
    {
        $this->model->guardarCategoria($_POST["descripcion"]);
        header("Location: /TPprogramacionWebII/Editor/panel");
    }
}