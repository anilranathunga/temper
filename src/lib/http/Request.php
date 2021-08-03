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
    protected Response $response;

    public function __construct()
    {
        $this->response = new JsonResponse();
    }

    /**
     * @return array|\src\lib\http\JsonResponse
     * Validate request and find the route from request url
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

    /**
     * @return string
     * Extract the route from url
     */
    public function getRout(): string
    {
        $urlSegments = explode("/",trim($_SERVER['REQUEST_URI'],"/") );

        $apiSegmentArrayKey = array_search("api",$urlSegments);

        if ($apiSegmentArrayKey===false || !array_key_exists($apiSegmentArrayKey+1,$urlSegments) ){
             $this->response->sendResponse([
                "status" => "error",
                "data" => "route not found"
            ]);
        }

        return $urlSegments[$apiSegmentArrayKey+1];
    }
}