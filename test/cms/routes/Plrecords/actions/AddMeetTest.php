<?php
namespace test\cms\routes\Plrecords\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Plrecords\actions\AddMeet
 */
class AddMeetTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Plrecords\views\AddMeetForm';
        $repoClass = '\cms\repositories\PlMeets';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $testedClass = '\cms\routes\Plrecords\actions\AddMeet';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $validatorMock = $this->getMock($validatorInterface);


        $this->testObj = new $testedClass($viewMock, $this->repoMock,
                                          $validatorMock);
    }


    public function validationCaseProvider()
    {
        return [
            [//meet not in existing
                ['meet' => 'foobar'],
                ['barbaz', 'bazbat'],
                true
            ],
            [//meet in existing
                ['meet' => 'foobar'],
                ['foobar', 'barbaz', 'bazbat'],
                false
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'meets', $this->testObj);
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
                       ->method('addMeet')
                       ->with($this->identicalTo($input));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider validationCaseProvider
     */
    public function testValidateInput($input, $meets, $passes)
    {
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($meets);

        if (!$passes) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
