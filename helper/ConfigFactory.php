<?php
include_once(__DIR__ . "/MyConexion.php");
include_once(__DIR__ . "/NewRouter.php");
include_once(__DIR__ . "/MustacheRenderer.php");

include_once(__DIR__ . "/../controller/LoginController.php");
include_once(__DIR__ . "/../controller/RegisterController.php");
include_once(__DIR__ . "/../controller/IndexController.php");
include_once(__DIR__ . "/../controller/HomeController.php");
include_once(__DIR__ . "/../controller/GameController.php");
include_once(__DIR__ . "/../controller/ValidarController.php");

include_once(__DIR__ . "/../model/LoginModel.php");
include_once(__DIR__ . "/../model/RegisterModel.php");
include_once(__DIR__ . "/../model/HomeModel.php");
include_once(__DIR__ . "/../model/GameModel.php");

include_once(__DIR__ . "/../vendor/mustache/src/Mustache/Autoloader.php");

Mustache_Autoloader::register();

class ConfigFactory
{
    private $config;
    private $objetos;
    private $conexion;
    private $renderer;

    public function __construct()
    {
        $this->config = parse_ini_file(__DIR__ . "/../config/config.ini");

        $this->conexion = MyConexion::getInstance(
            $this->config["server"],
            $this->config["user"],
            $this->config["pass"],
            $this->config["database"]
        );

        $this->renderer = new MustacheRenderer(__DIR__ . "/../vista");

        $this->objetos["router"] = new NewRouter($this, "LoginController", "mostrarLogin");
        $this->objetos["LoginController"] = new LoginController(
            new LoginModel($this->conexion),
            $this->renderer
        );

        $this->objetos["RegisterController"] = new RegisterController(
            new RegisterModel($this->conexion),
            $this->renderer
        );

        $this->objetos["IndexController"] = new IndexController($this->renderer);

        $this->objetos["HomeController"] = new HomeController(
            $this->renderer,
        new HomeModel($this->conexion)
        );

        $this->objetos["GameController"] = new GameController(
            $this->renderer,
            new GameModel($this->conexion)
        );

        $this->objetos["ValidarController"] = new ValidarController(
            new RegisterModel($this->conexion),
            $this->renderer
        );

    }


    public function get($objectName)
    {
        return $this->objetos[$objectName] ?? null;
    }
}