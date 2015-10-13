<?php
namespace test\cms\routes\Plrecords\views;

/**
 * @coversDefaultClass \cms\routes\Plrecords\views\CsvForm
 */
class CsvFormTest extends \test\helpers\cases\FormView
{
    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\PlRecords';
        $builderInterface =
            '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $testedClass = '\cms\routes\Plrecords\views\CsvForm';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $builderMock = $this->getMock($builderInterface);

        $mockedMethods = [
            'standardConstructor',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->repoMock,
                                                    $builderMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'records', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $fields = [
            'foo' => ['foo1', 'foo2', 'foo3'],
            'bar' => ['bar1', 'bar2', 'bar3'],
            'bat' => ['bat1', 'bat2', 'bat3'],
        ];
        $this->repoMock->expects($this->once())
                       ->method('getFields')
                       ->willReturn($fields);

        $this->testObj->fetchData();
        $this->assertEquals(
            $fields,
            $this->readAttribute($this->testObj, 'data')['fields']
        );
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     */
    public function testConstructView()
    {
        $this->testFetchData();
        $standard = 'lorem ipsum dolor';

        $this->testObj->expects($this->once())
                      ->method('standardConstructor')
                      ->willReturn($standard);

        $this->testObj->constructView();
        $content = $this->readAttribute($this->testObj, 'viewContent');
        $this->assertTrue(strlen($content) > strlen($standard));
    }
}
