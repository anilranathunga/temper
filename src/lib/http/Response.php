<?php

namespace src\lib\http;

interface Response
{
    /**
     * Send response for http request
     *
     * @return mixed
     */
    public function sendResponse(array $responseBody): void;
}