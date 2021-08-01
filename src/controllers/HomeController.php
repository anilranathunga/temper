<?php

namespace src\controllers;

use src\lib\http\JsonResponse;
use src\services\graph\GenerateWeeklyRetentionGraphData;

class HomeController
{
    /**
     *
     */
    public function index()
    {
        $weeklyRetentionGraphData = new GenerateWeeklyRetentionGraphData();
        $totalUsersPassedEachStep = $weeklyRetentionGraphData->init();

        new JsonResponse(["data"=>$totalUsersPassedEachStep]);// another API endpoint response
    }
}