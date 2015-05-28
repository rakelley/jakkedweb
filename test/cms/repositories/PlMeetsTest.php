<?php
namespace test\cms\repositories;

/**
 * @coversDefaultClass \cms\repositories\PlMeets
 */
class PlMeetsTest extends \test\helpers\cases\Base
{
    protected $modelMock;


    protected function setUp()
    {
        $modelClass = '\cms\models\PlMeets';
        $testedClass = '\cms\repositories\PlMeets';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->modelMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'meets', $this->testObj);
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $meets = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('meets'))
                        ->willReturn($meets);


        $this->assertEquals($meets, $this->testObj->getAll());
    }


    /**
     * @covers ::addMeet
     * @depends testConstruct
     */
    public function testAddMeet()
    {
        $input = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($input));

        $this->testObj->addMeet($input);
    }


    /**
     * @covers ::Delete
     * @depends testConstruct
     */
    public function testDelete()
    {
        $caseArray = ['lorem', 'ipsum'];
        $expectedArray = $caseArray;
        $caseString = 'dolor';
        $expectedString = [$caseString];

        $this->modelMock->expects($this->at(0))
                        ->method('Delete')
                        ->with($this->identicalTo($expectedArray));
        $this->modelMock->expects($this->at(1))
                        ->method('Delete')
                        ->with($this->identicalTo($expectedString));

        $this->testObj->Delete($caseArray);
        $this->testObj->Delete($caseString);
    }
}
