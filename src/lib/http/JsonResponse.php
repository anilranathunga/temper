<?php

namespace src\lib\http;

use JetBrains\PhpStorm\NoReturn;

class JsonResponse implements Response
{
    /**
     * @return mixed|void
     * render response
     */
    public function sendResponse(array $responseBody): void
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        echo json_encode($responseBody);
        exit();
    }
}