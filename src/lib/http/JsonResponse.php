<?php

namespace src\lib\http;

use JetBrains\PhpStorm\NoReturn;

class JsonResponse implements Response
{
    /**
     * @return mixed|void
     * render response
     */
    #[NoReturn] public function sendResponse(array $data)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        echo json_encode($data);
        exit();
    }
}