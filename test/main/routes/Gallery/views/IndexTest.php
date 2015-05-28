<?php
namespace test\main\routes\Gallery\views;

/**
 * @coversDefaultClass \main\routes\Gallery\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Gallery';
        $testedClass = '\main\routes\Gallery\views\Index';

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
        $this->assertAttributeEquals($this->repoMock, 'galleries',
                                     $this->testObj);
        $this->assertAttributeNotEmpty('metaRoute', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $index = [
            [
                'name' => 'foo',
                'title' => 'Foo Gallery',
                'image' => 'foo.jpg',
            ],
            [
                'name' => 'bar',
                'title' => 'Bar Gallery',
                'image' => 'bar.jpg',
            ],
            [
                'name' => 'baz',
                'title' => 'Gallery of Baz',
                'image' => 'baz.png',
            ],
        ];

        $this->repoMock->expects($this->once())
                       ->method('getIndex')
                       ->willReturn($index);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($index, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     */
    public function testConstructView()
    {
        $this->testFetchData();

        $this->standardConstructViewTest();
    }
}
