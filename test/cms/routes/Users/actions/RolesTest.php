<?php
namespace test\cms\routes\Users\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Users\actions\Roles
 */
class RolesTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $rolesMock;
    protected $ioMock;
    protected $userMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Users\views\RolesForm';
        $rolesClass = '\cms\repositories\Roles';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $ioInterface = '\rakelley\jhframe\interfaces\services\IIo';
        $userClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Users\actions\Roles';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->rolesMock = $this->getMockBuilder($rolesClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->ioMock = $this->getMock($ioInterface);

        $this->userMock = $this->getMockBuilder($userClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($viewMock, $this->rolesMock,
                                          $validatorMock, $this->ioMock,
                                          $this->userMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->rolesMock, 'roles', $this->testObj);
        $this->assertAttributeEquals($this->ioMock, 'io', $this->testObj);
        $this->assertAttributeEquals($this->userMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['username' => 'foobar', 'roles' => ['lorem', 'ipsum']];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->userMock->expects($this->once())
                       ->method('setRoles')
                       ->with($this->identicalTo($input['username']),
                              $this->identicalTo($input['roles']));

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
        } else {
            $roles = [['role' => 'foo'], ['role' => 'bar'], ['role' => 'baz'],
                      ['role' => 'bat'], ['role' => 'lorem'],
                      ['role' => 'ipsum']];
            $post = ['username' => 'foobar', 'foo' => 'foo', 'bat' => 'bat'];

            $this->rolesMock->expects($this->once())
                            ->method('getAll')
                            ->willReturn($roles);

            $this->ioMock->expects($this->once())
                         ->method('getInputTable')
                         ->with($this->identicalTo('post'))
                         ->willReturn($post);
        }

        Utility::callMethod($this->testObj, 'validateInput');
        if ($exists) {
            $this->assertThat(
                $this->readAttribute($this->testObj, 'input'),
                $this->arrayHasKey('roles')
            );
        }
    }
}
