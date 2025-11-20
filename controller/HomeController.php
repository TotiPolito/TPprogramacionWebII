<?php

class HomeController
{
    private $renderer;
    private $model;

    public function __construct($renderer, $model)
    {
        $this->renderer = $renderer;
        $this->model = $model;
    }

    public function Game()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["usuario"])) {
            header("Location:/TPprogramacionWebII/Login/loginForm");
            exit;
        }

        $_SESSION['partida_en_curso'] = false;
        $usuario = $_SESSION["usuario"];

        if ($usuario["rol"] !== "jugador") {
            switch ($usuario["rol"]) {
                case "editor":
                    header("Location:/TPprogramacionWebII/Editor/panel");
                    exit;
                case "admin":
                    header("Location: /TPprogramacionWebII/Admin/panel");
                    exit;
            }
        }
        $idUsuario = $usuario["id"];
        $usuarioActualizado = $this->model->obtenerUsuarioPorId($idUsuario);
        $_SESSION["usuario"] = $usuarioActualizado;

        $data = [
            "logueado" => true,
            "perfil" => $usuarioActualizado,
            "nombre_completo" => $usuarioActualizado["nombre_completo"],
            "puntaje" => $usuarioActualizado["puntaje"]
        ];

        $this->renderer->render("home", $data);
    }

    public function mostrarEditar() {

        if (!isset($_GET["id"])) {
            die("Falta ID en la URL");
        }

        $id = $_GET["id"];
        $perfil = $this->model->obtenerUsuarioPorId($id);

        $this->renderer->render("editarPerfil", ["perfil" => $perfil]);
    }

    public function editar() {

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Método inválido");
        }

        $id = $_POST["id"];
        $usuario = $_POST["usuario"];
        $password = $_POST["password"];

        if (empty($id)) {
            die("Falta el ID del usuario");
        }

        $fotoNombreFinal = null;

        if (isset($_FILES["foto_perfil"]) && $_FILES["foto_perfil"]["error"] === 0) {

            $rutaDestino = "public/imagenes/perfiles/";

            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }

            $extension = pathinfo($_FILES["foto_perfil"]["name"], PATHINFO_EXTENSION);
            $fotoNombreFinal = "perfil_" . $id . "_" . time() . "." . $extension;

            move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $rutaDestino . $fotoNombreFinal);
        }

        $this->model->editarPerfil($id, $usuario, $password, $fotoNombreFinal);

        header("Location: /TPprogramacionWebII/Home/Game");
        exit;
    }
}