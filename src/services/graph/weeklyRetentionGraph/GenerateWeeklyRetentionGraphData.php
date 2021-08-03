<?php

namespace src\services\graph\weeklyRetentionGraph;

use src\configs\Config;
use src\repositories\UserRepositoryInterface;

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

        foreach ($graphData as  $dataPerWeek){
            $orderReinstatedData = $this->getReversCumulativeSumOffArrayValues($dataPerWeek["data"]);

            $cumulativePercentages = [];
            $totalUsers = $dataPerWeek["total"];

            foreach ($orderReinstatedData as $usersCount){
                $cumulativePercentages[] = $totalUsers>0 ? round(($usersCount/$totalUsers)*100) : 0;
            }
            $graphDataWithCumValues[] = [
                "name" => $dataPerWeek["week"][0] . " to " . $dataPerWeek["week"][1],
                "data" => $cumulativePercentages,
            ];
        }
        return $graphDataWithCumValues;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getReversCumulativeSumOffArrayValues(array $data): array
    {
        $reversedSeries = array_reverse($data,true);

        $cumulativeValues = [];
        $cumValue = null;
        foreach ($reversedSeries as $key => $value){
            $cumValue += $value;
            $cumulativeValues[$key] = $cumValue;
        }

        //reinstate the order off array and return
        return array_reverse($cumulativeValues,true);

    }
}