<?php
namespace test\cms\models;

/**
 * @coversDefaultClass \cms\models\PlMeets
 */
class PlMeetsTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\cms\models\PlMeets';
    protected $traitedMethods = ['deleteOnValues', 'insertAll', 'selectAll'];


    public function meetResultProvider()
    {
        return [
            [//results
                [
                    ['meet' => 'foobar', 'date' => '2015-03-15 03:14:15'],
                    ['meet' => 'barbaz', 'date' => '2015-03-14 03:14:15'],
                    ['meet' => 'bazbat', 'date' => '2015-03-13 03:14:15'],
                ]
            ],
            [//no results
                null
            ],
        ];
    }


    /**
     * @covers ::Add
     */
    public function testAdd()
    {
        $input = ['meet' => 'foobar', 'date' => '2015-03-14 03:14:15'];

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
        $meets = ['foobar', 'barbaz', 'bazbat'];

        $this->testObj->expects($this->once())
                      ->method('deleteOnValues')
                      ->with($this->identicalTo($meets));
        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->Delete($meets);
    }


    /**
     * @covers ::getMeets
     * @dataProvider meetResultProvider
     */
    public function testGetMeets($result)
    {
        $this->testObj->expects($this->once())
                      ->method('selectAll')
                      ->willReturn($result);

        $column = $this->readAttribute($this->testObj, 'primary');
        $expected = ($result) ? array_column($result, $column) : null;

        $this->assertEquals($expected, $this->callMethod('getMeets'));
    }
}
