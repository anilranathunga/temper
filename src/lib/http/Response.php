<?php

namespace src\lib\http;

interface Response
{
    /**
     * @return mixed
     */
    public function sendResponse(array $responseBody);
}