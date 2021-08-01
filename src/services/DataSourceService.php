<?php

namespace src\services;

use src\repositories\UserRepositoryInterface;

class DataSourceService
{

    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function init(){

        $records = $this->repository->getUserData();
        $cleanedData = $this->cleanData($records);
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
        $itemsInARow = 0;
        foreach ($data as $key=>$row){

            if ($key == 0){
                $itemsInARow = sizeof($row);
            }else{
                if (sizeof($row)==$itemsInARow){
                    $cleanedData[] = $row;
                }
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