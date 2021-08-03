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
        return $this->getWeeklyRecords($weeks, $this->data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function getWeeks(array $data): array
    {
        $weeksArray = [];
        //week start with sunday of the start date's week
        if(!$this->isSunday($data[0][1])){
            $startDate = date(Config::DATE_FORMAT, strtotime('last Sunday', strtotime($data[0][1])));
        }else{
            $startDate = $data[0][1];
        }


//        var_dump($data[0][1]);
//        var_dump($startDate);
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
     * Get a week after date for given start date
     *
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
     *
     *
     * @param array $weeks
     * @param array $sortedData
     * @return array
     */
    public function getWeeklyRecords(array $weeks, array $sortedData): array
    {
        $weeklyRecords = [];

        foreach ($weeks as $key=>$week){
            $weeklyRecords[$key]["week"] = $week;
            $weeklyRecords[$key]["records"] = [];
            foreach ($sortedData as $record){

                $date = $record[1];
                $weekStartDate = $week[0];
                $weekEndDate = $week[1];
                if (($date >= $weekStartDate) && ($date <= $weekEndDate)){
                    $weeklyRecords[$key]["records"][] = $record;
                }
            }
        }

        return $weeklyRecords;
    }

    /**
     * @param $date
     * @return bool
     * Check whether passed date is a sunday
     */
    function isSunday($date): bool
    {
        $weekDay = date('w', strtotime($date));
        return $weekDay == 0;
    }
}