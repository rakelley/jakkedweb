<?php
namespace test\cms\routes\Quotes\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Quotes\actions\Add
 */
class AddTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Quotes\views\AddForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $repoClass = '\main\repositories\Quotes';
        $testedClass = '\cms\routes\Quotes\actions\Add';

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
        $this->assertAttributeEquals($this->repoMock, 'quotes', $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['quote' => 'lorem ipsum'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('addQuote')
                       ->with($this->identicalTo($input['quote']));

        $this->testObj->Proceed();
    }
}
