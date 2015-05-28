<?php
namespace test\cms\models;

/**
 * @coversDefaultClass \cms\models\SessionData
 */
class SessionDataTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\cms\models\SessionData';
    protected $traitedMethods = ['insertAll', 'selectOneByParameter',
                                 'setParameters'];


    /**
     * @covers ::Get
     */
    public function testGet()
    {
        $id = 1234;
        $row = ['id' => 1234, 'lorem' => 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('setParameters')
                      ->with($this->identicalTo(['id' => $id]));
        $this->testObj->expects($this->once())
                      ->method('selectOneByParameter')
                      ->willReturn($row);

        $this->assertEquals($row, $this->testObj->Get($id));
    }


    /**
     * @covers ::Add
     */
    public function testAdd()
    {
        $input = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('insertAll')
                      ->with($this->identicalTo($input));
        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->Add($input);
    }


    /**
     * @covers ::Remove
     */
    public function testRemove()
    {
        $keys = ['foo', 'bar', 'baz'];
        $expectedValues = array_merge($keys, $keys);
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('delete'),
                            $this->identicalTo($table))
                     ->will($this->returnSelf());

        $this->whereMock->expects($this->exactly(2))
                        ->method('In')
                        ->with($this->isType('string'),
                               $this->identicalTo($keys))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Execute')
                        ->with($this->identicalTo($expectedValues));

        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->Remove($keys);
    }
}
