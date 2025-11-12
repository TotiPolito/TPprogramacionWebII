<?php

class AdminController
{
    private $renderer;
    private $model;

    public function __construct($renderer, $model)
    {
        $this->renderer = $renderer;
        $this->model = $model;
    }

    public function panel()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== "admin") {
            header("Location:/TPprogramacionWebII/index.php?controller=Login&method=loginForm");
            exit;
        }

        $stats = $this->model->obtenerEstadisticasGenerales();

        $data = [
            "logueado" => true,
            "nombre_completo" => $_SESSION["usuario"]["nombre_completo"],
            "estadisticas" => $stats
        ];

        $this->renderer->render("panelAdmin", $data);
    }

    public function filtrar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== "admin") {
            header("Location:/TPprogramacionWebII/index.php?controller=Login&method=loginForm");
            exit;
        }

        $filtros = [
            "fecha_inicio" => $_POST["fecha_inicio"] ?? null,
            "fecha_fin" => $_POST["fecha_fin"] ?? null,
            "usuario" => $_POST["usuario"] ?? null
        ];

        $stats = $this->model->obtenerEstadisticasFiltradas($filtros);
        echo json_encode($stats);
    }

    public function descargarPDF()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== "admin") {
            header("Location:/TPprogramacionWebII/index.php?controller=Login&method=loginForm");
            exit;
        }

        $stats = $this->model->obtenerEstadisticasGenerales();

        require_once __DIR__ . '/../helper/pdfGenerator.php';
        PDFGenerator::generar($stats);
    }
}
