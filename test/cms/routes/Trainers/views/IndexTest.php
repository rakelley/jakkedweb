<?php
namespace test\cms\routes\Trainers\views;

/**
 * @coversDefaultClass \cms\routes\Trainers\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Trainers';
        $testedClass = '\cms\routes\Trainers\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedMethods = [
            'interpolatePlaceholders',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->repoMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('interpolatePlaceholders')
                      ->with($this->isType('string'), $this->isType('array'))
                      ->will($this->returnArgument(0));
    }


    public function dataCaseProvider()
    {
        return [
            [//no trainers
                null,
            ],
            [//trainers
                [
                    ['name' => 'foobar', 'visible' => true],
                    ['name' => 'barbaz', 'visible' => false],
                    ['name' => 'bazbat', 'visible' => true],
                ],
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'trainers',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider dataCaseProvider
     */
    public function testFetchData($trainers)
    {
        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($trainers);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($trainers, 'data', $this->testObj);
    }


    /**
     * @covers ::getSubViewList
     */
    public function testGetSubViewList()
    {
        $this->standardGetSubViewListTest();
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @depends testGetSubViewList
     * @dataProvider dataCaseProvider
     */
    public function testConstructView($data)
    {
        $this->testFetchData($data);
        $this->mockSubViews();

        $this->standardConstructViewTest();
    }
}
