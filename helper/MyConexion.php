<?php

class MyConexion
{
    private static $instance = null;
    private $conexion;

    private function __construct($server, $user, $pass, $database)
    {
        $this->conexion = new mysqli($server, $user, $pass, $database);

        if ($this->conexion->connect_error) {
            die("Error en la conexión: " . $this->conexion->connect_error);
        }
    }

    public static function getInstance($server = "localhost", $user = "root", $pass = "", $database = "preguntados")
    {
        if (self::$instance === null) {
            self::$instance = new MyConexion($server, $user, $pass, $database);
        }
        return self::$instance;
    }

    public function getConexion()
    {
        return $this->conexion;
    }
}