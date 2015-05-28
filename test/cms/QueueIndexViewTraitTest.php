<?php
namespace test\cms;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\QueueIndexViewTrait
 */
class QueueIndexViewTraitTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider,
        \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoInterface = '\main\IQueueRepository';
        $testedTrait = '\cms\QueueIndexViewTrait';

        $this->repoMock = $this->getMock($repoInterface);

        $this->testObj = $this->getMockForTrait($testedTrait);
        $properties = [
            'queueRepo' => $this->repoMock,
            'queueName' => 'foobar',
            'queueController' => 'bazbat',
        ];
        Utility::setProperties($properties, $this->testObj);
    }


    /**
     * @covers ::fetchData
     */
    public function testFetchData()
    {
        $items = [
            ['id' => 10, 'date' => '03-14-15 00:00:00'],
            ['id' => 20, 'date' => '03-14-14 00:00:00'],
            ['id' => 30, 'date' => '03-14-13 00:00:00'],
        ];

        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($items);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($items, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::fillTable
     * @depends testFetchData
     * @dataProvider booleanCaseProvider
     */
    public function testConstructView($withData)
    {
        if ($withData) {
            $this->testFetchData();
        }

        $this->standardConstructViewTest();
    }
}
