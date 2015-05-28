<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\Animals
 */
class AnimalsTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\Animals';
    protected $traitedMethods = ['deleteOnValues', 'selectAll'];


    /**
     * @covers ::Add
     */
    public function testAdd()
    {
        $table = $this->readAttribute($this->testObj, 'table');
        $columns = $this->readAttribute($this->testObj, 'columns');
        $animals = [
            ['number' => 001, 'name' => 'foobar'],
            ['number' => 004, 'name' => 'barbaz'],
            ['number' => 007, 'name' => 'bazbat'],
        ];
        $expectedArgs = [
            'columns' => $columns,
            'rows' => count($animals)
        ];
        $expectedValueCount = count($animals) * count($animals[0]);

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('insert'),
                            $this->identicalTo($table),
                            $this->identicalTo($expectedArgs))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('Execute')
                        ->with($this->countOf($expectedValueCount));

        $this->testObj->Add($animals);
    }


    /**
     * @covers ::Delete
     */
    public function testDelete()
    {
        $animals = [001, 004, 007];

        $this->testObj->expects($this->once())
                      ->method('deleteOnValues')
                      ->with($this->identicalTo($animals));

        $this->testObj->Delete($animals);
    }


    /**
     * @covers ::getAnimals
     */
    public function testGetAnimals()
    {
        $animals = [
            ['number' => 001, 'name' => 'foobar'],
            ['number' => 004, 'name' => 'barbaz'],
            ['number' => 007, 'name' => 'bazbat'],
        ];

        $this->testObj->expects($this->once())
                      ->method('selectAll')
                      ->willReturn($animals);

        $this->assertEquals($animals, $this->callMethod('getAnimals'));
    }
}
