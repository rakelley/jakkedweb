<?php
namespace test\cms\routes\Animals\actions;

/**
 * @coversDefaultClass \cms\routes\Animals\actions\Update
 */
class UpdateTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Animals';
        $testedClass = '\cms\routes\Animals\actions\Update';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'animals',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $this->repoMock->expects($this->once())
                       ->method('Update')
                       ->willReturn(true);

        $this->assertTrue($this->testObj->Proceed());
        $this->assertAttributeEmpty('error', $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testProceed
     */
    public function testProceedFailure()
    {
        $this->repoMock->expects($this->once())
                       ->method('Update')
                       ->willReturn(false);

        $this->assertFalse($this->testObj->Proceed());
        $this->assertAttributeNotEmpty('error', $this->testObj);
    }
}
