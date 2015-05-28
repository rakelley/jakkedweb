<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\Alertbanner
 */
class AlertbannerTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\Alertbanner';


    /**
     * @covers ::getBanner
     * @dataProvider queryResultProvider
     */
    public function testGetBanner($result)
    {
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('Fetch')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->callMethod('getBanner'));
    }


    /**
     * @covers ::setBanner
     */
    public function testSetBanner()
    {
        $input = ['lorem', 'ipsum'];
        $table = $this->readAttribute($this->testObj, 'table');
        $columns = $this->readAttribute($this->testObj, 'columns');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('update'),
                            $this->identicalTo($table),
                            $this->identicalTo(['columns' => $columns]))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo($columns),
                               $this->identicalTo($input))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('Execute');

        $this->callMethod('setBanner', [$input]);
    }
}
