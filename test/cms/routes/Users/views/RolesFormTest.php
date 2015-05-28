<?php
namespace test\cms\routes\Users\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Users\views\RolesForm
 */
class RolesFormTest extends \test\helpers\cases\FormView
{
    protected $rolesMock;
    protected $userMock;


    protected function setUp()
    {
        $rolesClass = '\cms\repositories\Roles';
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $userClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Users\views\RolesForm';

        $this->rolesMock = $this->getMockBuilder($rolesClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $builderMock = $this->getMock($builderInterface);

        $this->userMock = $this->getMockBuilder($userClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->rolesMock, $builderMock,
                                          $this->userMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->rolesMock, 'roles', $this->testObj);
        $this->assertAttributeEquals($this->userMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depend testConstruct
     */
    public function testFetchData()
    {
        $parameters = ['username' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $roles = [
            ['role' => 'foo', 'description' => 'foo role'],
            ['role' => 'bar', 'description' => 'bar role'],
            ['role' => 'baz', 'description' => 'baz role'],
            ['role' => 'bat', 'description' => 'bat role'],
            ['role' => 'lorem', 'description' => 'lorem role'],
            ['role' => 'ipsum', 'description' => 'ipsum role'],
        ];
        $relations = [
            'foo' => ['bar', 'baz'],
            'bar' => ['baz'],
            'lorem' => ['ipsum'],
        ];
        $userRoles = ['foo', 'bat'];
        $expected = ['roles' => $roles, 'relations' => $relations,
                     'userRoles' => $userRoles];

        $this->rolesMock->expects($this->once())
                        ->method('getAll')
                        ->willReturn($roles);
        $this->rolesMock->expects($this->once())
                        ->method('getRelations')
                        ->willReturn($relations);
        
        $this->userMock->expects($this->once())
                       ->method('getRoles')
                       ->with($this->identicalTo($parameters['username']))
                       ->willReturn($userRoles);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($expected, 'data', $this->testObj);
    }


    /**
     * @covers ::fillRoles
     * @depends testFetchData
     */
    public function testFillRoles()
    {
        $this->testFetchData();

        $content = Utility::callMethod($this->testObj, 'fillRoles');
        $this->assertTrue(strlen($content) > 1);
    }
}
