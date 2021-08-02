<?php

namespace src\repositories;

use src\configs\Config;
use src\repositories\UserRepositoryInterface;
use src\services\DataSourceService;

class UserCSVRepository implements UserRepositoryInterface
{

    /**
     * @return array
     */
    public function getUserData(): array
    {
        $records = array();
        $row = 1;
        if (($handle = fopen(Config::DATA_SOURCE_PATH, "r")) !== FALSE) {

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $num = count($data);
                $row++;

                for ($c=0; $c < $num; $c++) {
                    $records[] = explode(";",$data[$c]);
                }
            }
            fclose($handle);
        }

        $dataSourceService = new DataSourceService($records);

        return $dataSourceService->init();
        //return $records;
    }
}