<?php

namespace src\services\graph\weeklyRetentionGraph;

use src\configs\Config;
use src\repositories\UserCSVRepository;
use src\repositories\UserRepositoryInterface;
use src\services\DataSourceService;
use src\services\graph\weeklyRetentionGraph\FormatDataForGraph;

class GenerateWeeklyRetentionGraphData
{

    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $userRepo)
    {
     $this->repository = $userRepo;
    }

    /**
     * @return array
     */
    public function init(): array
    {
        $formatDataForGraphs = new FormatDataForGraph($this->repository->getUserData());
         $weeklyDataRecords = $formatDataForGraphs->init();

        $graphData = $this->getWeeklyStepWiseUsersCount($weeklyDataRecords);

        return $this->getTotalUsersPercentagePassedEachStep($graphData);
    }

    /**
     * @param $weeklyDataRecords
     * @return array
     * get users count on each step weekly
     */
    public function getWeeklyStepWiseUsersCount($weeklyDataRecords): array
    {
        $series = [];
        foreach ($weeklyDataRecords as $key=>$dataForWeek){

            $totalUsers = sizeof($dataForWeek["records"]);

            //set default % for each step
            $onboardingSteps = [];

            foreach (Config::ONBOARDING_STEPS as $step){ $onboardingSteps[$step] = 0; }

            $series[$key]["week"] = $dataForWeek["week"];
            $series[$key]["data"] = $onboardingSteps;
            $series[$key]["total"] = $totalUsers;

            foreach ($dataForWeek["records"] as $record){
                if (array_key_exists($record[2],$onboardingSteps)){
                    $series[$key]["data"][$record[2]] += 1;
                }
            }

//            foreach ($series[$key]["data"] as $step=>$usersCount){
//                $series[$key]["data"][$step] = $totalUsers>0 ? round(($usersCount/$totalUsers)*100) : 0;
//            }

        }

        return $series;
    }

    /**
     * @param $graphData
     * @return array
     * Get total users count passed each step
     */
    public function getTotalUsersPercentagePassedEachStep($graphData): array
    {
        $graphDataWithCumValues = [];

        foreach ($graphData as  $weekKey => $dataPerWeek){
            $reversedSeries = array_reverse($dataPerWeek["data"],true);

            $cumulativeValues = [];
            $cumValue = null;
            foreach ($reversedSeries as $key => $value){
                $cumValue += $value;
                $cumulativeValues[$key] = $cumValue;
            }

            $orderReinstatedData =  array_reverse($cumulativeValues,true);
            $cumulativePercentages = [];
            $totalUsers = $dataPerWeek["total"];

            foreach ($orderReinstatedData as $step=>$usersCount){
                $cumulativePercentages[$step] = $totalUsers>0 ? round(($usersCount/$totalUsers)*100) : 0;
            }
            $graphDataWithCumValues[] = [
                "name" => $dataPerWeek["week"][0] . " to " . $dataPerWeek["week"][1],
                "data" => $cumulativePercentages,
            ];
        }


        return $graphDataWithCumValues;
    }
}