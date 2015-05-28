<?php
namespace test\cms\routes\Page\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Page\actions\Delete
 */
class DeleteTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $pageMock;
    protected $navMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Page\views\DeleteForm';
        $pageClass = '\cms\repositories\FlatPage';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $navClass = '\main\repositories\Navigation';
        $testedClass = '\cms\routes\Page\actions\Delete';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->pageMock = $this->getMockBuilder($pageClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->navMock = $this->getMockBuilder($navClass)
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->testObj = new $testedClass($viewMock, $this->pageMock,
                                          $validatorMock, $this->navMock);
    }


    public function validationCaseProvider()
    {
        return [
            [//exists no entry
                true, false, false, true
            ],
            [//exists with entry
                true, true, false, true
            ],
            [//doesn't exist
                false, false, false, false
            ],
            [//has children
                true, true, true, false
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->pageMock, 'page', $this->testObj);
        $this->assertAttributeEquals($this->navMock, 'navEntries',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testProceed($hasEntry)
    {
        $input = ['route' => 'foobar'];
        Utility::setProperties(['input' => $input, 'hasNavEntry' => $hasEntry],
                               $this->testObj);

        $this->pageMock->expects($this->once())
                       ->method('deletePage')
                       ->with($this->identicalTo($input['route']));

        if ($hasEntry) {
            $this->navMock->expects($this->once())
                          ->method('deleteEntry')
                          ->with($this->identicalTo($input['route']));
        }

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider validationCaseProvider
     */
    public function testValidateInput($exists, $navExists, $children, $passes)
    {
        $input = ['route' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->pageMock->expects($this->once())
                       ->method('getPage')
                       ->with($this->identicalTo($input['route']))
                       ->willReturn($exists);

        if ($exists) {
            $this->navMock->expects($this->once())
                          ->method('entryExists')
                          ->with($this->identicalTo($input['route']))
                          ->willReturn($navExists);
            if ($navExists) {
                $this->navMock->expects($this->once())
                              ->method('entryHasChildren')
                              ->with($this->identicalTo($input['route']))
                              ->willReturn($children);
            }
        }

        if (!$passes) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }

        Utility::callMethod($this->testObj, 'validateInput');
    }
}
