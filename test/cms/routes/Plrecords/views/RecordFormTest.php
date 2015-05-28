<?php
namespace test\cms\routes\Plrecords\views;

/**
 * @coversDefaultClass \cms\routes\Plrecords\views\RecordForm
 */
class RecordFormTest extends \test\helpers\cases\FormView
{
    protected $repoMock;


    protected function setUp()
    {
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $repoClass = '\main\repositories\PlRecords';
        $testedClass = '\cms\routes\Plrecords\views\RecordForm';

        $builderMock = $this->getMock($builderInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedMethods = [
            'standardConstructor',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$builderMock,
                                                    $this->repoMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
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
     */
    public function testFetchData()
    {
        $fields = ['lorem', 'ipsum'];

        $this->repoMock->expects($this->once())
                       ->method('getFields')
                       ->willReturn($fields);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($fields, 'data', $this->testObj);
    }
}
