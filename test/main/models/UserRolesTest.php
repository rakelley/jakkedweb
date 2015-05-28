<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\UserRoles
 */
class UserRolesTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\UserRoles';
    protected $traitedMethods = ['deleteByParameter', 'setParameters',
                                 '__unset'];


    public function usersResultProvider()
    {
        return [
            [//result
                [['username' => 'foo'], ['username' => 'bar']]
            ],
            [//no result
                []
            ],
        ];
    }


    public function rolesResultProvider()
    {
        return [
            [//result
                [['role' => 'foo'], ['role' => 'bar']]
            ],
            [//no result
                []
            ],
        ];
    }


    public function rolesSettingCaseProvider()
    {
        return [
            [//with roles
                ['foo', 'bar', 'baz']
            ],
            [//without roles
                []
            ]
        ];
    }


    public function permissionsResultProvider()
    {
        return [
            [//result
                [['permission' => 'foo'], ['permission' => 'bar']]
            ],
            [//no result
                []
            ],
        ];
    }


    /**
     * @covers ::getUsersByPermission
     * @dataProvider usersResultProvider
     */
    public function testGetUsersByPermission($result)
    {
        $table = $this->readAttribute($this->testObj, 'table');
        $column = 'permission';
        $permission = 'foobar';

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table),
                            $this->arrayHasKey('select'))
                     ->will($this->returnSelf());

        $this->joinMock->expects($this->once())
                       ->method('Using')
                       ->with($this->isType('string'))
                       ->willReturn($this->dbMock);

        $this->whereMock->expects($this->once())
                        ->method('Equals')
                        ->with($this->identicalTo($column))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo($column),
                               $this->identicalTo([$column => $permission]))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $returnValue = $this->testObj->getUsersByPermission($permission);
        if ($result) {
            $this->assertEquals(count($result), count($returnValue));
        } else {
            $this->assertEquals(null, $returnValue);
        }
    }


    /**
     * @covers ::getRoles
     * @dataProvider rolesResultProvider
     */
    public function testGetRoles($result)
    {
        $key = 'username';
        $table = $this->readAttribute($this->testObj, 'table');
        $parameters = [$key => 'foobar'];
        $this->setUpParameters($parameters);

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table))
                     ->will($this->returnSelf());

        $this->whereMock->expects($this->once())
                        ->method('Equals')
                        ->with($this->identicalTo($key))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo($key),
                               $this->identicalTo($parameters))
                        ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $returnValue = $this->callMethod('getRoles');
        if ($result) {
            $this->assertEquals(count($result), count($returnValue));
        } else {
            $this->assertEquals(null, $returnValue);
        }
    }


    /**
     * @covers ::setRoles
     * @dataProvider rolesSettingCaseProvider
     */
    public function testSetRoles($roles)
    {
        $this->testObj->expects($this->once())
                      ->method('__unset')
                      ->with($this->identicalTo('roles'));

        if ($roles) {
            $table = $this->readAttribute($this->testObj, 'table');
            $parameters = ['username' => 'loremipsum'];
            $this->setUpParameters($parameters);

            $this->dbMock->expects($this->once())
                         ->method('newQuery')
                         ->with($this->identicalTo('insert'),
                                $this->identicalTo($table),
                                $this->logicalAnd(
                                    $this->arrayHasKey('columns'),
                                    $this->arrayHasKey('rows')
                                ))
                         ->will($this->returnSelf());

            $this->stmntMock->expects($this->once())
                            ->method('Execute')
                            ->with($this->countOf(count($roles) * 2));
        }

        $this->callMethod('setRoles', [$roles]);
    }


    /**
     * @covers ::unsetRoles
     */
    public function testUnsetRoles()
    {
        $this->testObj->expects($this->once())
                      ->method('deleteByParameter')
                      ->with($this->isType('string'));

        $this->callMethod('unsetRoles');
    }


    /**
     * @covers ::getPermissions
     * @dataProvider permissionsResultProvider
     */
    public function testGetPermissions($result)
    {
        $key = 'username';
        $table = $this->readAttribute($this->testObj, 'table');
        $parameters = [$key => 'foobar'];
        $this->setUpParameters($parameters);

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table),
                            $this->arrayHasKey('select'))
                     ->will($this->returnSelf());

        $this->joinMock->expects($this->once())
                       ->method('Using')
                       ->with($this->isType('string'))
                       ->willReturn($this->dbMock);

        $this->whereMock->expects($this->once())
                        ->method('Equals')
                        ->with($this->identicalTo($key))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo($key),
                               $this->identicalTo($parameters))
                        ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $returnValue = $this->callMethod('getPermissions');
        if ($result) {
            $this->assertEquals(count($result), count($returnValue));
        } else {
            $this->assertEquals(null, $returnValue);
        }
    }
}
