<?php
require_once("helper/QrGenerator.php");

class PerfilController {

    private $renderer;
    private $model;

    public function __construct($renderer, $model)
    {
        $this->renderer = $renderer;
        $this->model = $model;
    }

    public function mostrarPerfil()
    {
        if (!isset($_GET['id'])) {
            echo "ID de jugador no especificado";
            return;
        }

        $idJugador = $_GET['id'];
        $perfil = $this->model->obtenerPerfilPorId($idJugador);

        if ($perfil) {

            $urlPerfil = "/TPprogramacionWebII/Perfil/mostrarPerfil&id=" . $idJugador;
            $rutaQR = "public/imagenes/qrs/jugador_" . $idJugador . ".png";
            QrGenerator::generarQR($urlPerfil, $rutaQR);

            $this->renderer->render("perfil", [
                "perfil" => $perfil,
                "qr" => $rutaQR
            ]);
        } else {
            echo "Jugador no encontrado";
        }
    }

    public function mostrarEditar() {

        if (!isset($_GET["id"])) {
            die("Falta ID en la URL");
        }

        $id = $_GET["id"];
        $perfil = $this->model->obtenerPerfilPorId($id);

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

        header("Location: /TPprogramacionWebII/Perfil/mostrarPerfil&id=" . $id);
        exit;
    }
}
