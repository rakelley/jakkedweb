<?php
namespace test\cms\routes\Validate;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Validate\Validate
 */
class ValidateTest extends \test\helpers\cases\RouteController
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $actionMock;
    protected $resultMock;


    protected function setUp()
    {
        $actionInterface =
            '\rakelley\jhframe\interfaces\services\IActionController';
        $resultClass = '\rakelley\jhframe\classes\resources\ActionResult';
        $testedClass = '\cms\routes\Validate\Validate';

        $this->actionMock = $this->getMock($actionInterface);

        $this->resultMock = $this->getMockBuilder($resultClass)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods(null)
                              ->getMock();
        Utility::setProperties(['actionController' => $this->actionMock],
                               $this->testObj);

        $this->createRoutedMethodsList();
    }


    /**
     * Common logic for testing testb oject's methods
     * 
     * @param  string $method   Method to test
     * @param  bool   $withArgs Whether method passes args to executeAction
     * @param  bool   $success  Whether action is successful
     * @return void
     */
    public function standardValidation($method, $withArgs, $success)
    {
        $this->assertContains($method, $this->routedMethods);

        if ($withArgs) {
            $this->actionMock->expects($this->once())
                             ->method('executeAction')
                             ->with($this->isType('string'),
                                    $this->isType('array'))
                             ->willReturn($this->resultMock);
        } else {
            $this->actionMock->expects($this->once())
                             ->method('executeAction')
                             ->with($this->isType('string'))
                             ->willReturn($this->resultMock);
        }

        $this->resultMock->expects($this->once())
                         ->method('getSuccess')
                         ->willReturn($success);
        if ($success) {
            $this->resultMock->expects($this->once())
                             ->method('setContent')
                             ->with($this->identicalTo(true))
                             ->will($this->returnSelf());
        } else {
            $this->resultMock->expects($this->once())
                             ->method('setContent')
                             ->with($this->isType('string'))
                             ->will($this->returnSelf());
        }
        $this->resultMock->expects($this->once())
                         ->method('Render');

        $this->testObj->$method();
    }


    /**
     * @covers ::Date
     * @dataProvider booleanCaseProvider
     */
    public function testDate($success)
    {
        $this->standardValidation('date', true, $success);
    }


    /**
     * @covers ::dateTime
     * @dataProvider booleanCaseProvider
     */
    public function testDateTime($success)
    {
        $this->standardValidation('dateTime', true, $success);
    }


    /**
     * @covers ::pageNotExists
     * @dataProvider booleanCaseProvider
     */
    public function testPageNotExists($success)
    {
        $this->standardValidation('pageNotExists', false, $success);
    }


    /**
     * @covers ::spamCheck
     * @dataProvider booleanCaseProvider
     */
    public function testSpamCheck($success)
    {
        $this->standardValidation('spamCheck', false, $success);
    }


    /**
     * @covers ::userExists
     * @dataProvider booleanCaseProvider
     */
    public function testUserExists($success)
    {
        $this->standardValidation('userExists', false, $success);
    }


    /**
     * @covers ::userNotExists
     * @dataProvider booleanCaseProvider
     */
    public function testUserNotExists($success)
    {
        $this->standardValidation('userNotExists', false, $success);
    }
}
