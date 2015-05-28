<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\Trainers
 */
class TrainersTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\Trainers';
    protected $traitedMethods = ['deleteByParameter', 'insertAll', 'selectAll',
                                 'selectOneByParameter', 'setParameters',
                                 'updateByPrimary'];


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
     * @covers ::Delete
     */
    public function testDelete()
    {
        $name = 'foobar';

        $this->testObj->expects($this->once())
                      ->method('setParameters')
                      ->with($this->identicalTo(['name' => $name]));
        $this->testObj->expects($this->once())
                      ->method('deleteByParameter');
        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->Delete($name);
    }


    /**
     * @covers ::getAllTrainers
     */
    public function testGetAllTrainers()
    {
        $trainers = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectAll')
                      ->willReturn($trainers);

        $this->assertEquals($trainers, $this->callMethod('getAllTrainers'));
    }


    /**
     * @covers ::getAllVisible
     * @dataProvider queryResultProvider
     */
    public function testGetAllVisible($result)
    {
        $column = 'visible';
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table))
                     ->will($this->returnSelf());

        $this->whereMock->expects($this->once())
                        ->method('Equals')
                        ->with($this->identicalTo($column))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo($column),
                               $this->arrayHasKey($column))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->callMethod('getAllVisible'));
    }


    /**
     * @covers ::getTrainer
     */
    public function testGetTrainer()
    {
        $result = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectOneByParameter')
                      ->willReturn($result);

        $this->assertEquals($result, $this->callMethod('getTrainer'));
    }


    /**
     * @covers ::setTrainer
     */
    public function testSetTrainer()
    {
        $input = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('updateByPrimary')
                      ->with($this->identicalTo($input));

        $this->callMethod('setTrainer', [$input]);
    }
}
