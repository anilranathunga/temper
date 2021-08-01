<?php
namespace src;
use src\configs\Config;
use src\lib\http\JsonResponse;
use src\lib\http\Request;

class App
{
    /**
     * @throws \Exception
     */
    public function init()
    {
        $request = new Request();
        $routeData = $request->resolveRoute();

        $controller = Config::CONTROLLERS_PATH . $routeData[1];

        $action = $routeData[2];

        if (class_exists($controller)){
            $controllerObject = new $controller();
            $controllerObject->$action();
        }else{
            throw new \Exception("Controller not found");
        }
    }
}