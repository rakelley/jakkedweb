<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\Page
 */
class PageTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\Page';
    protected $traitedMethods = ['deleteByParameter', 'insertAll',
                                 'selectOneByParameter', 'setParameters',
                                 'updateByPrimary'];


    public function groupResultProvider()
    {
        return [
            [//result
                [['route' => 'foo'], ['route' => 'bar']],
            ],
            [//no result
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
     * @covers ::getGroup
     * @dataProvider groupResultProvider
     */
    public function testGetGroup($result)
    {
        $keyName = 'pagegroup';
        $key = 'foobar';
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table),
                            $this->arrayHasKey('select'))
                     ->will($this->returnSelf());

        $this->whereMock->expects($this->once())
                        ->method('Equals')
                        ->with($this->identicalTo($keyName))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo($keyName),
                               $this->identicalTo([$keyName => $key]))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $returnValue = $this->testObj->getGroup($key);
        if ($result) {
            $this->assertEquals(count($result), count($returnValue));
        } else {
            $this->assertEquals(null, $returnValue);
        }
    }


    /**
     * @covers ::getPages
     * @dataProvider queryResultProvider
     */
    public function testGetPages($result)
    {
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table))
                     ->will($this->returnSelf());
        $this->dbMock->expects($this->once())
                     ->method('addOrder')
                     ->with($this->logicalOr(
                            $this->arrayHasKey('DESC'),
                            $this->arrayHasKey('ASC')
                        ))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->callMethod('getPages'));
    }


    /**
     * @covers ::getPage
     */
    public function testGetPage()
    {
        $result = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectOneByParameter')
                      ->willReturn($result);

        $this->assertEquals($result, $this->callMethod('getPage'));
    }


    /**
     * @covers ::setPage
     */
    public function testSetPage()
    {
        $input = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('updateByPrimary')
                      ->with($this->identicalTo($input));

        $this->callMethod('setPage', [$input]);
    }


    /**
     * @covers ::unsetPage
     */
    public function testUnsetPage()
    {
        $this->testObj->expects($this->once())
                      ->method('deleteByParameter');

        $this->callMethod('unsetPage');
    }
}
