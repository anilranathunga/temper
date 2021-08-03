<?php

namespace src\services;


class DataSourceService
{
    protected array $records;

    public function __construct(array $records)
    {
        $this->records = $records;

    }

    /**
     * @return array
     */
    public function init(): array
    {
        $cleanedData = $this->cleanData($this->records);
        return $this->sortByDate($cleanedData);
    }

    /**
     * @param array $data
     * @return array
     * remove records which doesn't contain all fields
     */
    function cleanData(array $data): array
    {
        $cleanedData = array();
        foreach ($data as $key=>$row){

            //ignore field labels row and clean rows
            if ($key != 0){
                $dirtyRow = array_filter($row,function ($item){ return (empty($item) && $item!=0) ?true:false; });

                if (empty($dirtyRow))
                    $cleanedData[] = $row;
            }
        }

        return $cleanedData;
    }

    /**
     * @param array $data
     * @return array
     * sort ascending order by date
     */
    public function sortByDate(array $data): array
    {
        usort($data, function ($a, $b){
            $dateTimestamp1 = strtotime($a[1]);
            $dateTimestamp2 = strtotime($b[1]);

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });
        return $data;
    }
}