<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\PlRecords
 */
class PlRecordsTest extends \test\helpers\cases\Base
{
    protected $modelMock;


    protected function setUp()
    {
        $modelClass = '\main\models\PlRecords';
        $testedClass = '\main\repositories\PlRecords';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->modelMock);
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'recordModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getFields
     * @depends testConstruct
     */
    public function testGetFields()
    {
        $fields = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('fields'))
                        ->willReturn($fields);

        $this->assertEquals($fields, $this->testObj->getFields());
    }


    /**
     * @covers ::getIndex
     * @depends testConstruct
     */
    public function testGetIndex()
    {
        $index = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('index'))
                        ->willReturn($index);

        $this->assertEquals($index, $this->testObj->getIndex());
    }


    /**
     * @covers ::getQuery
     * @depends testConstruct
     */
    public function testGetQuery()
    {
        $parameters = ['lorem', 'ipsum'];
        $records = ['foo', 'bar'];

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo($parameters));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('query'))
                        ->willReturn($records);

        $this->assertEquals($records, $this->testObj->getQuery($parameters));
    }


    /**
     * @covers ::getRecord
     * @depends testConstruct
     */
    public function testGetRecord()
    {
        $parameters = ['lorem', 'ipsum'];
        $record = ['foo', 'bar'];

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo($parameters));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('record'))
                        ->willReturn($record);

        $this->assertEquals($record, $this->testObj->getRecord($parameters));
    }


    /**
     * @covers ::setRecord
     * @depends testConstruct
     */
    public function testSetRecord()
    {
        $record = ['foo', 'bar'];

        $this->modelMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('record'),
                               $this->identicalTo($record));

        $this->testObj->setRecord($record);
    }
}
