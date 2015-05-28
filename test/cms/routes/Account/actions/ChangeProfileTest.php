<?php
namespace test\cms\routes\Account\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Account\actions\ChangeProfile
 */
class ChangeProfileTest extends \test\helpers\cases\Base
{
    protected $accountMock;
    protected $username = 'foobar';


    protected function setUp()
    {
        $viewClass = '\cms\routes\Account\views\ProfileForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $accountClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Account\actions\ChangeProfile';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->accountMock = $this->getMockBuilder($accountClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->testObj = new $testedClass($viewMock, $validatorMock,
                                          $this->accountMock);
        Utility::setProperties(['parameters' => ['username' => $this->username]],
                               $this->testObj);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->accountMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['profile' => 'lorem ipsum'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->accountMock->expects($this->once())
                          ->method('setProfile')
                          ->with($this->identicalTo($this->username),
                                 $this->identicalTo($input['profile']));

        $this->testObj->Proceed();
    }
}
