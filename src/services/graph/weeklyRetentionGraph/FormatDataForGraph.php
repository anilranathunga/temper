<?php

namespace src\services\graph\weeklyRetentionGraph;

use src\configs\Config;

class FormatDataForGraph
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function init(): array
    {
        $weeks = $this->getWeeks($this->data);
        return $this->getWeeklyData($weeks, $this->data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function getWeeks(array $data): array
    {
        $weeksArray = [];
        //week start with sunday of the start date's week
        $startDate = date(Config::DATE_FORMAT, strtotime('last Sunday', strtotime($data[0][1])));
        $endDateOfWeekTs =  strtotime($startDate);
        $endDateTs =  strtotime($data[sizeof($data)-1][1]);

        while ($endDateOfWeekTs<$endDateTs){

            $endDateOfWeek = $this->getEndDateOfWeek($startDate);
            $weeksArray[] = [$startDate, $endDateOfWeek];

            $endDateOfWeekTs = strtotime($endDateOfWeek);

            $startDateTs = strtotime("+1 day", $endDateOfWeekTs);
            $startDate = date(Config::DATE_FORMAT, $startDateTs);

        }
        return $weeksArray;

    }

    /**
     * @param $startDate
     * @return string
     */
    public function getEndDateOfWeek($startDate): string
    {
        $dateTs = strtotime($startDate);
        $ts = strtotime("+6 day", $dateTs);
        return date(Config::DATE_FORMAT, $ts);
    }

    /**
     * @param array $weeks
     * @param array $sortedData
     * @return array
     */
    public function getWeeklyData(array $weeks, array $sortedData): array
    {
        $weeklyData = [];

        foreach ($weeks as $key=>$week){
            $weeklyData[$key]["week"] = $week;
            $weeklyData[$key]["records"] = [];
            foreach ($sortedData as $record){

                $date = $record[1];
                $weekStartDate = $week[0];
                $weekEndDate = $week[1];

                if (($date >= $weekStartDate) && ($date <= $weekEndDate)){
                    $weeklyData[$key]["records"][] = $record;
                }
            }
        }

        return $weeklyData;
    }
}