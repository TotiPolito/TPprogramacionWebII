<?php

class ReportesController {
    private $model;
    private $renderer;

    public function __construct($model, $renderer) {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    // Muestra todos los reportes pendientes
    public function listar() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'editor') {
            header("Location: /TPprogramacionWebII/Login/loginForm");
            exit;
        }

        $reportes = $this->model->obtenerReportes();
        $this->renderer->render("reportes", ["reportes" => $reportes]);
    }

    public function aceptar() {  // Se acepta el reporte
        $idReporte = $_GET['idReporte'];

        $this->model->aceptarReporte($idReporte);

        header("Location: /TPprogramacionWebII/Editor/panel");
        exit;
    }

    // // Se rechaza el reporte
    public function rechazar() {
        $idReporte = $_GET['idReporte'];

        $this->model->rechazarReporte($idReporte);

        header("Location: /TPprogramacionWebII/Editor/panel");
        exit;
    }
}