<?php
namespace test\cms\routes\Plrecords\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Plrecords\actions\Update
 */
class UpdateTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Plrecords\views\RecordForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $repoClass = '\main\repositories\PlRecords';
        $testedClass = '\cms\routes\Plrecords\actions\Update';

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


    public function validationCaseProvider()
    {
        return [
            [//valid
                ['foo' => 'lorem', 'bar' => 'ipsum', 'baz' => 'dolor'],
                [
                    'foo' => ['lorem', 'ipsum', 'dolor'],
                    'bar' => ['lorem', 'ipsum', 'dolor'],
                    'baz' => ['lorem', 'ipsum', 'dolor'],
                ],
                true
            ],
            [//foo invalid
                ['foo' => 'lorem', 'bar' => 'ipsum', 'baz' => 'dolor'],
                [
                    'foo' => ['ipsum', 'dolor'],
                    'bar' => ['lorem', 'ipsum', 'dolor'],
                    'baz' => ['lorem', 'ipsum', 'dolor'],
                ],
                false
            ],
            [//baz invalid
                ['foo' => 'lorem', 'bar' => 'ipsum', 'baz' => 'dolor'],
                [
                    'foo' => ['lorem', 'ipsum', 'dolor'],
                    'bar' => ['lorem', 'ipsum', 'dolor'],
                    'baz' => ['lorem', 'ipsum'],
                ],
                false
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'records',
                                     $this->testObj);
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
                       ->method('setRecord')
                       ->with($this->identicalTo($input));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider validationCaseProvider
     */
    public function testValidateInput($input, $fields, $passes)
    {
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getFields')
                       ->willReturn($fields);

        if (!$passes) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
