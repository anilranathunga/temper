<?php

namespace src\lib\http;
use http\Params;
use phpDocumentor\Reflection\Types\This;
use PHPUnit\Util\Json;
use src\configs\Config;
use src\Routes;
use src\lib\http\JsonResponse;
use src\lib\http\Response;

class Request
{
    protected array $parameters;
    protected Response $response;

    public function __construct()
    {
        $this->setParameters();
        $this->response = new JsonResponse();
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
    public function resolveRoute(): array | null
    {
        $route = $this->getRout();

        $routes = Routes::$routes;
        $routesData = [];

        if ( !array_key_exists($route,$routes) || $routes[$route][0] !== $_SERVER['REQUEST_METHOD']){

            $this->response->sendResponse([
                "status" => "error",
                "data" => "route not found"
            ]);

        }else{
            $routesData = $routes[$route];
        }

        return $routesData;
    }

    public function getRout(): string
    {
        $urlSegments = explode("/",trim($_SERVER['REQUEST_URI'],"/") );

        $apiSegmentArrayKey = array_search("api",$urlSegments);

        if ($apiSegmentArrayKey===false){
             $this->response->sendResponse([
                "status" => "error",
                "data" => "route not found"
            ]);
        }

        return $urlSegments[$apiSegmentArrayKey+1];
    }
}