<?php

namespace src\lib\http;

class JsonResponse implements Response
{
    /**
     * @return mixed|void
     */
    public function sendResponse(array $data)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        echo json_encode($data);
        exit();
    }
}