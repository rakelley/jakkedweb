<?php
namespace test\cms\routes\Nav\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Nav\actions\Edit
 */
class EditTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Nav\views\EditForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $repoClass = '\main\repositories\Navigation';
        $testedClass = '\cms\routes\Nav\actions\Edit';

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


    public function proceedCaseProvider()
    {
        return [
            [//with parent
                ['foo' => 'bar', 'parent' => 'baz', 'lorem' => 'ipsum']
            ],
            [//without parent
                ['foo' => 'bar', 'lorem' => 'ipsum']
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'navEntries',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     * @dataProvider proceedCaseProvider
     */
    public function testProceed($input)
    {
        Utility::setProperties(['input' => $input], $this->testObj);

        $expected = (array_key_exists('parent', $input)) ? $input :
                    array_merge($input, ['parent' => null]);

        $this->repoMock->expects($this->once())
                       ->method('setEntry')
                       ->with($this->identicalTo($expected));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testValidateInput($exists)
    {
        $input = ['foo' => 'bar', 'route' => 'baz'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('entryExists')
                       ->with($this->identicalTo($input['route']))
                       ->willReturn($exists);

        if (!$exists) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
