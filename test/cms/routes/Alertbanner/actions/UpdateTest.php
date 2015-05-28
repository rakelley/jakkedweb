<?php
namespace test\cms\routes\Alertbanner\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Alertbanner\actions\Update
 */
class UpdateTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Alertbanner\views\UpdateForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $repoClass = '\main\repositories\Alertbanner';
        $testedClass = '\cms\routes\Alertbanner\actions\Update';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($viewMock, $validatorMock,
                                          $this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'banner', $this->testObj);
    }


    /**
     * @covers ::Proceed
     */
    public function testProceed()
    {
        $input = ['foo' => 'bar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('setBanner')
                       ->with($this->identicalTo($input));

        $this->testObj->Proceed();
    }
}
