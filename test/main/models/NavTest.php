<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\Nav
 */
class NavTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\Nav';
    protected $traitedMethods = ['deleteByParameter', 'insertAll',
                                 'selectOneByParameter', 'setParameters',
                                 'updateByPrimary'];


    public function parentResultProvider()
    {
        return [
            [//results
                [['title' => 'foo'], ['title' => 'bar']]
            ],
            [//no results
                [],
            ],
        ];
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
     * @covers ::getEntries
     * @dataProvider queryResultProvider
     */
    public function testGetEntries($result)
    {
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table))
                     ->will($this->returnSelf());
        $this->dbMock->expects($this->once())
                     ->method('addOrder')
                     ->with($this->arrayHasKey('ASC'))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->callMethod('getEntries'));
    }


    /**
     * @covers ::getEntry
     */
    public function testGetEntry()
    {
        $result = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectOneByParameter')
                      ->willReturn($result);

        $this->assertEquals($result, $this->callMethod('getEntry'));
    }


    /**
     * @covers ::setEntry
     */
    public function testSetEntry()
    {
        $input = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('updateByPrimary')
                      ->with($this->identicalTo($input));

        $this->callMethod('setEntry', [$input]);
    }


    /**
     * @covers ::unsetEntry
     */
    public function testUnsetEntry()
    {
        $this->testObj->expects($this->once())
                      ->method('deleteByParameter');

        $this->callMethod('unsetEntry');
    }


    /**
     * @covers ::getParentList
     * @dataProvider parentResultProvider
     */
    public function testGetParentList($result)
    {
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table),
                            $this->arrayHasKey('select'))
                     ->will($this->returnSelf());

        $this->whereMock->expects($this->once())
                        ->method('isNull')
                        ->with($this->isType('string'))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $returnValue = $this->callMethod('getParentList');
        if (!$result) {
            $this->assertEquals(null, $returnValue);
        } else {
            $this->assertEquals(count($result), count($returnValue));
        }
    }
}
