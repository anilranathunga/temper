<?php

namespace src\lib\http;

class JsonResponse implements Response
{
    protected array $responseBody;

    /**
     * JsonResponse constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->responseBody = $data;
        $this->sendResponse();
    }

    /**
     * @return mixed|void
     */
    public function sendResponse()
    {
        header('Content-Type: application/json');
        echo json_encode($this->responseBody);
    }
}