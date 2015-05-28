<?php
namespace test\cms\routes\Page\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Page\actions\Edit
 */
class EditTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Page\views\EditForm';
        $repoClass = '\cms\repositories\FlatPage';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $testedClass = '\cms\routes\Page\actions\Edit';

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
            [//passes
                ['route' => 'foobar', 'priority' => 0.5],
                true,
                true
            ],
            [//page doesn't exist
                ['route' => 'foobar', 'priority' => 0.5],
                false,
                false
            ],
            [//priority <0
                ['route' => 'foobar', 'priority' => -1],
                true,
                false
            ],
            [//priority >1
                ['route' => 'foobar', 'priority' => 2.5],
                true,
                false
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'page', $this->testObj);
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
                       ->method('setPage')
                       ->with($this->identicalTo($input));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider validationCaseProvider
     */
    public function testValidateInput($input, $exists, $passes)
    {
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getPage')
                       ->with($this->identicalTo($input['route']))
                       ->willReturn($exists);

        if (!$passes) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }

        Utility::callMethod($this->testObj, 'validateInput');
    }
}
