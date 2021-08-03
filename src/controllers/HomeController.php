<?php

namespace src\controllers;

use JetBrains\PhpStorm\NoReturn;
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

    /**
     * Get step wise user retention data
     */
    public function getRetentionGraphData(): void
    {
        $userCsvRepo = new UserCSVRepository();

        $weeklyRetentionGraphData = new GenerateWeeklyRetentionGraphData($userCsvRepo);

        $totalUsersPassedEachStep = $weeklyRetentionGraphData->init();

        $this->response->sendResponse(["data"=>$totalUsersPassedEachStep]);
    }
}