<?php

namespace src\controllers;

use src\lib\http\JsonResponse;

class HomeController
{
    public function index()
    {
        new JsonResponse(["in index"]);
    }

}