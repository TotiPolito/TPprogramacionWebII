<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include("helper/ConfigFactory.php");


$configFactory = new ConfigFactory();

$router = $configFactory->get("router");

$controller = $_GET["controller"] ?? "login";
$method = $_GET["method"] ?? "mostrarLogin";

$router->executeController($controller, $method);
