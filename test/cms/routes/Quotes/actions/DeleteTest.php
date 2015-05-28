<?php
namespace test\cms\routes\Quotes\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Quotes\actions\Delete
 */
class DeleteTest extends \test\helpers\cases\Base
{
    protected $inputMock;
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Quotes\views\DeleteForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $inputInterface = '\rakelley\jhframe\interfaces\services\IInput';
        $repoClass = '\main\repositories\Quotes';
        $testedClass = '\cms\routes\Quotes\actions\Delete';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->inputMock = $this->getMock($inputInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($viewMock, $validatorMock,
                                          $this->inputMock, $this->repoMock);
    }


    public function searchResultProvider()
    {
        return [
            [//no matches
                null
            ],
            [//matches
                ['lorem', 'ipsum', 'dolor']
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->inputMock, 'inputLib',
                                     $this->testObj);
        $this->assertAttributeEquals($this->repoMock, 'quotes', $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['lorem', 'ipsum'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('deleteQuotes')
                       ->with($this->identicalTo($input));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider searchResultProvider
     */
    public function testValidateInput($input)
    {
        $this->inputMock->expects($this->once())
                        ->method('searchKeys')
                        ->with($this->isType('string'), $this->isType('string'),
                               $this->isType('array'))
                        ->willReturn($input);

        if (!$input) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
