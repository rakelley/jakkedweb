<?php
namespace test\cms\routes\Account\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Account\actions\Delete
 */
class DeleteTest extends \test\helpers\cases\Base
{
    protected $authMock;
    protected $mailMock;
    protected $articleMock;
    protected $accountMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Account\views\DeleteForm';
        $authClass = '\cms\repositories\UserAuth';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $mailInterface = '\rakelley\jhframe\interfaces\services\IMail';
        $articleClass = '\main\repositories\Article';
        $accountClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Account\actions\Delete';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->authMock = $this->getMockBuilder($authClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->mailMock = $this->getMock($mailInterface);

        $this->articleMock = $this->getMockBuilder($articleClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->accountMock = $this->getMockBuilder($accountClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->testObj = new $testedClass($viewMock, $this->authMock,
                                          $validatorMock, $this->mailMock,
                                          $this->articleMock,
                                          $this->accountMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->authMock, 'auth', $this->testObj);
        $this->assertAttributeEquals($this->mailMock, 'mail', $this->testObj);
        $this->assertAttributeEquals($this->articleMock, 'article',
                                     $this->testObj);
        $this->assertAttributeEquals($this->accountMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @covers ::<private>
     */
    public function testProceed()
    {
        $parameters = ['username' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->articleMock->expects($this->once())
                          ->method('revertAuthor')
                          ->with($this->identicalTo($parameters['username']));

        $this->accountMock->expects($this->once())
                          ->method('deleteUser')
                          ->with($this->identicalTo($parameters['username']));

        $this->mailMock->expects($this->once())
                       ->method('Send')
                       ->with($this->identicalTo($parameters['username']),
                              $this->isType('string'), $this->isType('string'));

        $this->authMock->expects($this->once())
                       ->method('logOut');

        $this->testObj->Proceed();
    }
}
