<?php

namespace src\controllers;

use src\lib\http\JsonResponse;
use src\lib\http\Response;
use src\repositories\UserCSVRepository;
use src\services\graph\weeklyRetentionGraph\GenerateWeeklyRetentionGraphData;

class HomeController
{
    protected Response $response;

    public function __construct()
    {
        $this->response = new JsonResponse();
    }

    public function getRetentionGraphData()
    {
        $userCsvRepo = new UserCSVRepository();

        $weeklyRetentionGraphData = new GenerateWeeklyRetentionGraphData($userCsvRepo);

        $totalUsersPassedEachStep = $weeklyRetentionGraphData->init();

        $this->response->sendResponse(["data"=>$totalUsersPassedEachStep]);
    }
}