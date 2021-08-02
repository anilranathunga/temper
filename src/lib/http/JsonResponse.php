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
        echo json_encode($data);
        exit();
    }
}