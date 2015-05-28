<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\PlRecords
 */
class PlRecordsTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\PlRecords';
    protected $traitedMethods = ['insertAll', 'selectOneByParameter',
                                 'setParameters', 'updateByPrimary', '__get'];


    public function setRecordCaseProvider()
    {
        return [
            [//no existing
                ['record' => 100, 'lorem' => 'ipsum'],
                null
            ],
            [//existing lower record
                ['record' => 100, 'lorem' => 'ipsum'],
                ['record' => 10, 'lorem' => 'ipsum'],
            ],
            [//existing higher record
                ['record' => 100, 'lorem' => 'ipsum'],
                ['record' => 1000, 'lorem' => 'ipsum'],
            ],
        ];
    }


    /**
     * @covers ::getIndex
     */
    public function testGetIndex()
    {
        $primary = $this->readAttribute($this->testObj, 'primary');

        //pick random values as comma delimitted string for each primary column
        $values = ['foo', 'bar', 'baz', 'bat', 'lorem', 'ipsum'];
        $result = array_map(
            function($i) use ($values) {
                return implode(',', array_rand(array_flip($values), 3));
            },
            range(1, count($primary))
        );

        $this->dbMock->expects($this->once())
                     ->method('setQuery')
                     ->with($this->isType('string'))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('Fetch')
                        ->willReturn($result);

        $returnValue = $this->callMethod('getIndex');
        $this->assertEquals(count($returnValue), count($primary));
        $this->assertInternalType('array', $returnValue[0]);
    }


    /**
     * @covers ::getQuery
     * @dataProvider queryResultProvider
     */
    public function testGetQuery($result)
    {
        $keyPool = $this->readAttribute($this->testObj, 'primary');
        $valuePool = ['foo', 'bar', 'baz', 'bat', 'lorem', 'ipsum'];
        $queryKeys = array_rand(array_flip($keyPool), 3);
        $queryValues = array_rand(array_flip($valuePool), 3);
        $parameters = array_combine($queryKeys, $queryValues);
        $this->setUpParameters($parameters);

        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table))
                     ->will($this->returnSelf());
        $this->dbMock->expects($this->once())
                     ->method('addOrder')
                     ->with($this->logicalOr(
                            $this->arrayHasKey('ASC'),
                            $this->arrayHasKey('DESC')
                        ))
                     ->will($this->returnSelf());

        $this->joinMock->expects($this->once())
                       ->method('Using')
                       ->with($this->identicalTo('meet'))
                       ->willReturn($this->dbMock);

        $this->whereMock->expects($this->once())
                        ->method('Equals')
                        ->with($this->identicalTo($queryKeys),
                               $this->identicalTo('AND'))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo($queryKeys),
                               $this->identicalTo($parameters))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->callMethod('getQuery'));
    }


    /**
     * @covers ::getFields
     */
    public function testGetFields()
    {
        $primary = $this->readAttribute($this->testObj, 'primary');

        //pick random values as comma delimitted string for each primary column
        //plus the meet column
        $values = ['foo', 'bar', 'baz', 'bat', 'lorem', 'ipsum'];
        $result = array_map(
            function($i) use ($values) {
                return implode(',', array_rand(array_flip($values), 3));
            },
            range(1, count($primary)+1)
        );

        $this->dbMock->expects($this->once())
                     ->method('setQuery')
                     ->with($this->isType('string'))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('Fetch')
                        ->willReturn($result);

        $returnValue = $this->callMethod('getFields');
        $this->assertEquals(count($returnValue), count($primary)+1);
        $this->assertInternalType('array', $returnValue[0]);
    }


    /**
     * @covers ::getRecord
     */
    public function testGetRecord()
    {
        $result = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectOneByParameter')
                      ->willReturn($result);

        $this->assertEquals($result, $this->callMethod('getRecord'));
    }


    /**
     * @covers ::setRecord
     * @dataProvider setRecordCaseProvider
     */
    public function testSetRecord($input, $existing)
    {
        $this->testObj->expects($this->once())
                      ->method('setParameters')
                      ->with($this->identicalTo($input));
        $this->testObj->expects($this->once())
                      ->method('__get')
                      ->with($this->identicalTo('record'))
                      ->willReturn($existing);

        if (!$existing) {
            $this->testObj->expects($this->once())
                          ->method('insertAll')
                          ->with($this->identicalTo($input));
        } elseif ($existing['record'] < $input['record']) {
            $this->testObj->expects($this->once())
                          ->method('updateByPrimary')
                          ->with($this->identicalTo($input));
        } else {
            $this->testObj->expects($this->never())
                          ->method('insertAll');
            $this->testObj->expects($this->never())
                          ->method('updateByPrimary');
        }

        $this->callMethod('setRecord', [$input]);
    }
}
