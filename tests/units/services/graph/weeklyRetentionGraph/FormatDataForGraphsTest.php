<?php

namespace tests\units\services\graph\weeklyRetentionGraph;

use phpDocumentor\Reflection\Types\This;
use PHPUnit\Framework\TestCase;
use src\services\graph\weeklyRetentionGraph\FormatDataForGraph;

class FormatDataForGraphsTest extends TestCase
{

    protected array $records = [];
    protected array $weeks = [];
    protected FormatDataForGraph $formatDataForGraphObject;

    protected function setUp(): void
    {
        $this->records = [
            [3121,	'2016-07-17',	40	,0,	0],
            [3122,	'2016-07-20',	45	,0,	0],
            [3123,	'2016-07-23',	45	,0,	0],
            [3124,	'2016-08-01',	50	,0,	0],
            [3125,	'2016-08-03',	40	,0,	0],
            [3126,	'2016-08-05',	55	,0,	0],
        ];

        $this->weeks = [
            ['2016-07-17','2016-07-23'],
            ['2016-07-24','2016-07-30'],
            ['2016-07-31','2016-08-06']
        ];

        $this->formatDataForGraphObject = new FormatDataForGraph($this->records);
    }

    public function test_get_weeks_between_min_and_max_date_range_in_data_records()
    {

        $expectedResult = [
            ['2016-07-17','2016-07-23'],
            ['2016-07-24','2016-07-30'],
            ['2016-07-31','2016-08-06']
        ];

        $result = $this->formatDataForGraphObject->getWeeks($this->records);

        $this->assertEqualsCanonicalizing($expectedResult, $result);
    }

    public function test_it_gives_the_day_after_a_week()
    {
        $startDate = '2016-07-17';
        $expectedResult = '2016-07-23';

        $result = $this->formatDataForGraphObject->getEndDateOfWeek($startDate);

        $this->assertEquals($result,$expectedResult);
    }

    public function test_it_gives_weekly_records_for_given_weeks()
    {
        $expectedResult =  [
            [
                'week'=> ['2016-07-17', '2016-07-23'],
                'records'=> [
                    [3121,	'2016-07-17',	40	,0,	0],
                    [3122,	'2016-07-20',	45	,0,	0],
                    [3123,	'2016-07-23',	45	,0,	0]
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

        $result = $this->formatDataForGraphObject->getWeeklyData($this->weeks, $this->records);

        $this->assertEqualsCanonicalizing($expectedResult, $result);
    }
}