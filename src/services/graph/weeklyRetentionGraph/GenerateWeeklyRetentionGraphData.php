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

        $graphData = $this->generateWeeklyGraphData($weeklyDataRecords);

        return $this->getTotalUsersPassedEachStep($graphData);
    }

    /**
     * @param $weeklyDataRecords
     * @return array
     */
    public function generateWeeklyGraphData($weeklyDataRecords): array
    {
        $series = [];
        foreach ($weeklyDataRecords as $key=>$dataForWeek){

            $totalUsers = sizeof($dataForWeek["records"]);

            //set default % for each step
            $onboardingSteps = [];

            foreach (Config::ONBOARDING_STEPS as $step){ $onboardingSteps[$step] = 0; }

            $series[$key]["week"] = $dataForWeek["week"];
            $series[$key]["series"] = $onboardingSteps;
            $series[$key]["total"] = $totalUsers;
            foreach ($dataForWeek["records"] as $record){
                if (array_key_exists($record[2],$onboardingSteps)){
                    $series[$key]["series"][$record[2]] += 1;
                }
            }

            foreach ($series[$key]["series"] as $step=>$usersCount){
                $series[$key]["series"][$step] = round(($usersCount/$totalUsers)*100);
            }

        }

        return $series;
    }

    /**
     * @param $graphData
     * @return array
     */
    public function getTotalUsersPassedEachStep($graphData): array
    {
        $graphDataWithCumValues = [];

        foreach ($graphData as $dataPerWeek){
            $reversedSeries = array_reverse($dataPerWeek["series"],true);

            $cumulativeValues = [];
            $cumValue = null;
            foreach ($reversedSeries as $key=>$value){
                $cumValue += $value;
                $cumulativeValues[$key] = $cumValue;
            }
            $graphDataWithCumValues[]=[
                "name" => $dataPerWeek["week"][0] . " to " . $dataPerWeek["week"][1],
                "data" => array_reverse($cumulativeValues,true)
            ];
        }
        return $graphDataWithCumValues;
    }
}