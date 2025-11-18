<?php

class ReportarController {
    private $model;
    private $renderer;

    public function __construct($model, $renderer) {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function reporte() {
        $idPregunta = $_GET["idPregunta"] ?? null;
        $respuestaCorrecta = isset($_GET["respuestaCorrecta"]) && $_GET["respuestaCorrecta"] === "true";

        if (!$idPregunta) {
            header("Location: /TPprogramacionWebII/Home/Game");
            exit;
        }

        $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);

        $this->renderer->render("reportarPregunta", [
            "pregunta" => $pregunta,
            "respuestaCorrecta" => $respuestaCorrecta == "true",
        ]);
    }

    // Guarda el reporte en la base de datos
    public function guardar() {
        if (!isset($_SESSION["usuario"])) {
            header("Location: /TPprogramacionWebII/Login/loginForm");
            exit;
        }

        $idUsuario = $_SESSION["usuario"]["id"];
        $idPregunta = $_POST["idPregunta"];
        $motivo = trim($_POST["motivo"]);

        if (empty($motivo)) {
            $this->renderer->render("reportarPregunta", [
                "error" => "Debe ingresar un motivo.",
                "pregunta" => ["id" => $idPregunta]
            ]);
            return;
        }

        $this->model->guardarReporte($idPregunta, $idUsuario, $motivo);

        $this->renderer->render("reportarPregunta", [
            "mensaje" => "El reporte fue enviado correctamente. ¡Gracias por tu ayuda!"
        ]);
    }
}