<?php
namespace test\main\routes\Records\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Records\views\PlForm
 */
class PlFormTest extends \test\helpers\cases\FormViewCustomContent
{
    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\PlRecords';
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $testedClass = '\main\routes\Records\views\PlForm';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $builderMock = $this->getMock($builderInterface);

        $mockedMethods = [
            'standardConstructor',//defined by parent
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
        $req = $this->readAttribute($this->testObj, 'requiredIndex');
        $indexes = [
            'lorem' => ['foo', 'bar', 'baz'],
            'ipsum' => [10, 20, 30],
            $req => ['dolor', 'sit', 'amet'],
        ];

        $this->repoMock->expects($this->once())
                       ->method('getIndex')
                       ->willReturn($indexes);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($indexes, 'data', $this->testObj);
    }


    /**
     * @covers ::fillIndexes
     * @covers ::fillIndex
     * @depends testFetchData
     */
    public function testFillIndexes()
    {
        $this->testFetchData();

        $result = Utility::callMethod($this->testObj, 'fillIndexes');

        $this->assertTrue(strlen($result) > 1);
    }
}
