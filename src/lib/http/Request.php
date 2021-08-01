<?php

namespace src\lib\http;
use src\configs\Config;
use src\Routes;
use src\lib\http\JsonResponse;

class Request
{
    protected array $parameters;

    public function __construct()
    {
        $this->setParameters();
    }

    /**
     * @return $this
     */
    public function setParameters(): Request
    {
        $parameters = [];
        switch ($_SERVER["REQUEST_METHOD"]){
            case "POST":
                $parameters = $_POST;
                break;
            case "GET":
                $parameters = $_GET;
                break;
        }
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array|\src\lib\http\JsonResponse
     */
    public function resolveRoute(): array | JsonResponse
    {
        $urlSegments = explode("/",trim($_SERVER['REQUEST_URI'],"/") );
        $apiSegmentArrayKey = array_search("api",$urlSegments);

        $route = $urlSegments[$apiSegmentArrayKey+1];

        $routes = Routes::$routes;
        $routesData = $routes[$route];

        if (!array_key_exists($route,$routes) || $routesData[0] !== $_SERVER['REQUEST_METHOD']){
            return new JsonResponse(["route not fount"]);
        }

        return $routesData;
    }
}