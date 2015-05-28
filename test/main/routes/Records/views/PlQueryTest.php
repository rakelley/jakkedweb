<?php
namespace test\main\routes\Records\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Records\views\PlQuery
 */
class PlQueryTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\PlRecords';
        $testedClass = '\main\routes\Records\views\PlQuery';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    public function queryProvider()
    {
        return [
            [//normal case
                [
                    'gender' => 'men',
                    'lift' => 'deadlift',
                ],
                [
                    [
                        'name' => 'Foo Bar',
                        'record' => '320',
                        'meet' => 'Bars 2014',
                        'date' => '03-14-2015',
                        'division' => 'Open',
                        'gear' => 'Raw',
                        'class' => '125',
                        'lift' => 'Deadlift',
                    ],
                    [
                        'name' => 'Baz Bat',
                        'record' => '400',
                        'meet' => 'Bars 2013',
                        'date' => '03-14-2014',
                        'division' => 'Master 33-39',
                        'gear' => 'Equipped',
                        'class' => '100',
                        'lift' => 'Deadlift',
                    ],
                    [
                        'name' => 'Lorem Ipsum',
                        'record' => '350',
                        'meet' => 'Bars 2013',
                        'date' => '03-14-2014',
                        'division' => 'Open',
                        'gear' => 'Raw',
                        'class' => 'SHW',
                        'lift' => 'Deadlift',
                    ],
                ],
            ],
            [//no results case
                [
                    'gender' => 'women',
                    'gear' => 'equipped',
                    'class' => '90',
                ],
                null
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'records',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider queryProvider
     */
    public function testFetchData($parameters, $records)
    {
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getQuery')
                       ->with($this->identicalTo($parameters))
                       ->willReturn($records);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($records, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @dataProvider queryProvider
     */
    public function testConstructView($parameters, $records)
    {
        $this->testFetchData($parameters, $records);

        $this->standardConstructViewTest();
    }
}
