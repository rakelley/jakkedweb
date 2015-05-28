<?php
namespace test\main\routes\Trainers\views;

/**
 * @coversDefaultClass \main\routes\Trainers\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider,
        \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Trainers';
        $testedClass = '\main\routes\Trainers\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'trainers',
                                     $this->testObj);
        $this->assertAttributeNotEmpty('metaRoute', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $trainers = [
            [
                'name' => 'foo',
                'photo' => 'foo.jpg',
                'profile' => 'lorem ipsum profile',
            ],
            [
                'name' => 'bar',
                'photo' => null,
                'profile' => 'lorem ipsum profile',
            ],
            [
                'name' => 'baz',
                'photo' => 'baz.jpg',
                'profile' => null,
            ],
        ];

        $this->repoMock->expects($this->once())
                       ->method('getVisible')
                       ->willReturn($trainers);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($trainers, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
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
