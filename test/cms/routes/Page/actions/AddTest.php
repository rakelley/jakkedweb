<?php
namespace test\cms\routes\Page\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Page\actions\Add
 */
class AddTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $viewClass = '\cms\routes\Page\views\NewForm';
        $repoClass = '\cms\repositories\FlatPage';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $testedClass = '\cms\routes\Page\actions\Add';

        $configMock = $this->getMock($configInterface);
        $configMock->method('Get')
                   ->with($this->identicalTo('APP'),
                          $this->identicalTo('base_path'))
                   ->willReturn('http://cms.example.com');

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $mockedMethods = [
            'getConfig',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getConfig')
                      ->willReturn($configMock);
        Utility::callConstructor($this->testObj, [$viewMock, $this->repoMock,
                                                  $validatorMock]);
    }


    public function validationCaseProvider()
    {
        return [
            [//passes
                ['route' => 'foobar', 'priority' => 0.5],
                false,
                true
            ],
            [//page exists
                ['route' => 'foobar', 'priority' => 0.5],
                true,
                false
            ],
            [//priority <0
                ['route' => 'foobar', 'priority' => -1],
                false,
                false
            ],
            [//priority >1
                ['route' => 'foobar', 'priority' => 2.5],
                false,
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
        $this->assertAttributeNotEmpty('basePath', $this->testObj);
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
                       ->method('addPage')
                       ->with($this->identicalTo($input));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::getResult
     * @depends testConstruct
     */
    public function testGetResult()
    {
        $input = ['route' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $result = $this->testObj->getResult();
        $this->assertTrue(strlen($result) > 1);
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
