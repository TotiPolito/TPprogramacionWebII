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
        $rutaImagen = null;

        if (!empty($_FILES["imagen"]["name"])) {
            $ruta = "public/imagenesPreguntas/";
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }

            $imagenNombre = time() . "_" . basename($_FILES["imagen"]["name"]);
            $rutaCompleta = $ruta . $imagenNombre;

            move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaCompleta);

            $rutaImagen = $imagenNombre;
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