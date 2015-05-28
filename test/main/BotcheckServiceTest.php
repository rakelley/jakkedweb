<?php
namespace test\main;

/**
 * @coversDefaultClass \main\BotcheckService
 */
class BotcheckServiceTest extends \test\helpers\cases\Base
{
    protected $inputMock;


    protected function setUp()
    {
        $inputInterface = '\rakelley\jhframe\interfaces\services\IInput';
        $testedClass = '\main\BotcheckService';

        $this->inputMock = $this->getMock($inputInterface);

        $this->testObj = new $testedClass($this->inputMock);
    }


    public function validationCaseProvider()
    {
        return [
            [//valid
                ['phone' => 5555555555],
                true
            ],
            [//company filled
                ['company' => 'lorem ipsum', 'phone' => 5555555555],
                false
            ],
            [//phone not filled
                [],
                false
            ],
            [//phone incorrectly filled
                ['phone' => 5558675309],
                false
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->inputMock, 'input', $this->testObj);
    }


    /**
     * @covers ::getField
     */
    public function testGetField()
    {
        $this->assertTrue(strlen($this->testObj->getField()) > 1);
    }


    /**
     * @covers ::validateField
     * @depends testConstruct
     * @dataProvider validationCaseProvider
     */
    public function testValidateField($input, $passes)
    {
        $this->inputMock->expects($this->once())
                        ->method('getList')
                        ->with($this->isType('array'),
                               $this->identicalTo('post'),
                               $this->identicalTo(true))
                        ->willReturn($input);

        if (!$passes) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }

        $this->testObj->validateField();
    }
}
