<?php
namespace test\cms\models;

/**
 * @coversDefaultClass \cms\models\Roles
 */
class RolesTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\cms\models\Roles';
    protected $traitedMethods = ['selectAll'];


    /**
     * @covers ::getRoles
     */
    public function testGetRoles()
    {
        $roles = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectAll')
                      ->willReturn($roles);

        $this->assertEquals($roles, $this->callMethod('getRoles'));
    }
}
