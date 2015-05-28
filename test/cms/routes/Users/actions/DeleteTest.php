<?php
namespace test\cms\routes\Users\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Users\actions\Delete
 */
class DeleteTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $articleMock;
    protected $userMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Users\views\DeleteForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $articleClass = '\main\repositories\Article';
        $userClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Users\actions\Delete';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->articleMock = $this->getMockBuilder($articleClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->userMock = $this->getMockBuilder($userClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($viewMock, $validatorMock,
                                          $this->articleMock, $this->userMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->articleMock, 'articles',
                                     $this->testObj);
        $this->assertAttributeEquals($this->userMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['username' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->articleMock->expects($this->once())
                          ->method('revertAuthor')
                          ->with($this->identicalTo($input['username']));

        $this->userMock->expects($this->once())
                       ->method('deleteUser')
                       ->with($this->identicalTo($input['username']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testValidateInput($exists)
    {
        $input = ['username' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->userMock->expects($this->once())
                       ->method('userExists')
                       ->with($this->identicalTo($input['username']))
                       ->willReturn($exists);

        if (!$exists) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
