<?php

namespace tests\units\services\graph\weeklyRetentionGraph;

use PHPUnit\Framework\TestCase;
use src\repositories\UserCSVRepository;
use src\services\graph\weeklyRetentionGraph\FormatDataForGraph;
use src\services\graph\weeklyRetentionGraph\GenerateWeeklyRetentionGraphData;

class GenerateWeeklyRetentionGraphDataTest extends TestCase
{

    protected array $weeklyDataRecords;
    protected array $weeklyGraphData;

    protected GenerateWeeklyRetentionGraphData $generateWeeklyRetentionGraphDataObject;

    protected function setUp(): void
    {
        $this->weeklyDataRecords = [
            [
                'week'=> ['2016-07-17', '2016-07-23'],
                'records'=> [
                    [3121,	'2016-07-19',	40	,0,	0],
                    [3122,	'2016-07-20',	45	,0,	0],
                    [3123,	'2016-07-21',	45	,0,	0]
                ]
            ],
            [
                'week'=> ['2016-07-24', '2016-07-30'],
                'records'=> [],
            ],
            [
                'week'=> ['2016-07-31', '2016-08-06'],
                'records'=> [
                    [3124,	'2016-08-01',	50	,0,	0],
                    [3125,	'2016-08-03',	40	,0,	0],
                    [3126,	'2016-08-05',	55	,0,	0],
                ]
            ]
        ];

        $this->weeklyGraphData = [
            [
                "week" => ['2016-07-17', '2016-07-23'],
                "data" => [ "35" => 0, "40"=> 1, "45"=> 2, "50"=> 0, "55"=> 0, "60"=> 0, "65"=> 0, "95"=> 0, "99"=> 0, "100"=> 0],
                "total" => 3
            ],
            [
                "week"=>['2016-07-24', '2016-07-30'],
                "data" => ["35" => 0, "40"=> 0, "45"=> 0, "50"=> 0, "55"=> 0, "60"=> 0, "65"=> 0, "95"=> 0, "99"=> 0, "100"=> 0],
                "total" => 0
            ],
            [
                "week"=>['2016-07-31', '2016-08-06'],
                "data" => ["35" => 0, "40"=> 1, "45"=> 0, "50"=> 1, "55"=> 1, "60"=> 0, "65"=> 0, "95"=> 0, "99"=> 0, "100"=> 0],
                "total" => 3
            ],
        ];

        $userCsvRepo = new UserCSVRepository();

        $this->generateWeeklyRetentionGraphDataObject = new GenerateWeeklyRetentionGraphData($userCsvRepo);

    }

    public function test_it_gives_weekly_stepwise_users()
    {
        $expectedResult = [
            [
                "week" => ['2016-07-17', '2016-07-23'],
                "data" => [ "35" => 0, "40"=> 1, "45"=> 2, "50"=> 0, "55"=> 0, "60"=> 0, "65"=> 0, "95"=> 0, "99"=> 0, "100"=> 0],
                "total" => 3
            ],
            [
                "week"=>['2016-07-24', '2016-07-30'],
                "data" => ["35" => 0, "40"=> 0, "45"=> 0, "50"=> 0, "55"=> 0, "60"=> 0, "65"=> 0, "95"=> 0, "99"=> 0, "100"=> 0],
                "total" => 0
            ],
            [
                "week"=>['2016-07-31', '2016-08-06'],
                "data" => ["35" => 0, "40"=> 1, "45"=> 0, "50"=> 1, "55"=> 1, "60"=> 0, "65"=> 0, "95"=> 0, "99"=> 0, "100"=> 0],
                "total" => 3
            ],
        ];
        $result = $this->generateWeeklyRetentionGraphDataObject->getWeeklyStepWiseUsersCount($this->weeklyDataRecords);

        $this->assertEqualsCanonicalizing($expectedResult, $result);
    }

    public function test_it_gives_total_user_percentage_passed_each_step()
    {

        $expectedResult = [
            [
                "name" => '2016-07-17 to 2016-07-23',
                "data" => [ "35" => 100, "40"=> 100, "45"=> 67, "50"=> 0, "55"=> 0, "60"=> 0, "65"=> 0, "95"=> 0, "99"=> 0, "100"=> 0]
            ],
            [
                "name"=>'2016-07-24 to 2016-07-30',
                "data" => ["35" => 0, "40"=> 0, "45"=> 0, "50"=> 0, "55"=> 0, "60"=> 0, "65"=> 0, "95"=> 0, "99"=> 0, "100"=> 0]
            ],
            [
                "name"=>'2016-07-31 to 2016-08-06',
                "data" => ["35" => 100, "40"=> 100, "45"=> 67, "50"=> 67, "55"=> 33, "60"=> 0, "65"=> 0, "95"=> 0, "99"=> 0, "100"=> 0]
            ],
        ];

        $result = $this->generateWeeklyRetentionGraphDataObject->getTotalUsersPercentagePassedEachStep($this->weeklyGraphData);

        $this->assertEqualsCanonicalizing($expectedResult, $result);
    }
}