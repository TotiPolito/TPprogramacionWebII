<?php
require_once("model/RegisterModel.php");

class ValidarController {
    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function validarCuenta(){
        $token = $_GET['token'] ?? null;

        if($token && $this->model->activarCuenta($token)){
            $this->renderer->render("validacionExitosa");
        } else {
            $this->renderer->render("validacionError");
        }
    }
}
