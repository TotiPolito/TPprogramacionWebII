<?php

class NewRouter
{
    private $configFactory;
    private $defaultController;
    private $defaultMethod;

    public function __construct($configFactory, $defaultController, $defaultMethod)
    {
        $this->configFactory = $configFactory;
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
    }

    public function executeController($controllerParam, $methodParam)
    {
        $controller = $this->getControllerFrom($controllerParam);

        $this->executeMethodFromController($controller, $methodParam);
    }

    private function getControllerFrom($controllerName)
    {
        $controllerName = $this->getControllerName($controllerName);
        $controller = $this->configFactory->get($controllerName);

        if ($controller === null) {
            die("Error: El controller '$controllerName' no fue encontrado. Revisa ConfigFactory.php y los includes.");
        }

        return $controller;
    }

    private function executeMethodFromController($controller, $methodName)
    {
        $methodName = $this->getMethodName($controller, $methodName);

        if (!method_exists($controller, $methodName)) {
            die("Error: El método '$methodName' no existe en el controller '". get_class($controller) . "'.");
        }

        call_user_func([$controller, $methodName]);
    }

    public function getControllerName($controllerName)
    {
        if ($controllerName) {
            // elimina extensión .php si la hay
            $controllerName = preg_replace('/\.php$/', '', $controllerName);
            return ucfirst($controllerName) . 'Controller';
        } else {
            return $this->defaultController;
        }
    }

    public function getMethodName($controller, $methodName)
    {
        return $methodName ?: $this->defaultMethod;
    }
}