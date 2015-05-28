<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\Users
 */
class UsersTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\Users';
    protected $traitedMethods = ['deleteByParameter', 'selectOneByParameter',
                                 'setParameters',
                                 'updateSingleByPrimaryParameter', '__get'];


    public function userPropertyCaseProvider()
    {
        return [
            [//user found
                [
                    'fullname' => 'Foo Bar',
                    'username' => 'foo@bar.com',
                    'password' => 'abcde1234',
                    'lastlogin' => '2015-03-14 00:00:00',
                    'profile' => 'lorem ipsum',
                ]
            ],
            [//user not found
                null
            ],
        ];
    }


    /**
     * @covers ::Add
     */
    public function testAdd()
    {
        $input = ['lorem', 'ipsum'];
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('insert'),
                            $this->identicalTo($table),
                            $this->arrayHasKey('columns'))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->isType('array'),
                               $this->identicalTo($input))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('Execute');

        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->Add($input);
    }


    /**
     * @covers ::Delete
     */
    public function testDelete()
    {
        $this->testObj->expects($this->once())
                      ->method('deleteByParameter');
        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->Delete();
    }


    /**
     * @covers ::getAllUsers
     * @dataProvider queryResultProvider
     */
    public function testGetAllUsers($result)
    {
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table),
                            $this->arrayHasKey('select'))
                     ->will($this->returnSelf());
        $this->dbMock->expects($this->once())
                     ->method('addOrder')
                     ->with($this->logicalOr(
                            $this->arrayHasKey('ASC'),
                            $this->arrayHasKey('DESC')
                        ))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->callMethod('getAllUsers'));
    }


    /**
     * @covers ::getFullname
     * @dataProvider userPropertyCaseProvider
     */
    public function testGetFullname($user)
    {
        $this->testObj->expects($this->once())
                      ->method('__get')
                      ->with($this->identicalTo('user'))
                      ->willReturn($user);

        $expected = ($user) ? $user['fullname'] : null;
        $this->assertEquals($expected, $this->callMethod('getFullName'));
    }


    /**
     * @covers ::getLastlogin
     * @dataProvider userPropertyCaseProvider
     */
    public function testGetLastlogin($user)
    {
        $this->testObj->expects($this->once())
                      ->method('__get')
                      ->with($this->identicalTo('user'))
                      ->willReturn($user);

        $expected = ($user) ? $user['lastlogin'] : null;
        $this->assertEquals($expected, $this->callMethod('getLastlogin'));
    }


    /**
     * @covers ::setLastlogin
     */
    public function testSetLastLogin()
    {
        $value = '2014-03-14 00:00:00';

        $this->testObj->expects($this->once())
                      ->method('updateSingleByPrimaryParameter')
                      ->with($this->identicalTo('lastlogin'),
                             $this->identicalTo($value));

        $this->callMethod('setLastlogin', [$value]);
    }


    /**
     * @covers ::getPassword
     * @dataProvider userPropertyCaseProvider
     */
    public function testGetPassword($user)
    {
        $this->testObj->expects($this->once())
                      ->method('__get')
                      ->with($this->identicalTo('user'))
                      ->willReturn($user);

        $expected = ($user) ? $user['password'] : null;
        $this->assertEquals($expected, $this->callMethod('getPassword'));
    }


    /**
     * @covers ::setPassword
     */
    public function testSetPassword()
    {
        $value = 'abcde';

        $this->testObj->expects($this->once())
                      ->method('updateSingleByPrimaryParameter')
                      ->with($this->identicalTo('password'),
                             $this->identicalTo($value));

        $this->callMethod('setPassword', [$value]);
    }


    /**
     * @covers ::getProfile
     * @dataProvider userPropertyCaseProvider
     */
    public function testGetProfile($user)
    {
        $this->testObj->expects($this->once())
                      ->method('__get')
                      ->with($this->identicalTo('user'))
                      ->willReturn($user);

        $expected = ($user) ? $user['profile'] : null;
        $this->assertEquals($expected, $this->callMethod('getProfile'));
    }


    /**
     * @covers ::setProfile
     */
    public function testSetProfile()
    {
        $value = 'lorem ipsum dolor';

        $this->testObj->expects($this->once())
                      ->method('updateSingleByPrimaryParameter')
                      ->with($this->identicalTo('profile'),
                             $this->identicalTo($value));

        $this->callMethod('setProfile', [$value]);
    }


    /**
     * @covers ::getUser
     */
    public function testGetUser()
    {
        $result = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectOneByParameter')
                      ->willReturn($result);

        $this->assertEquals($result, $this->callMethod('getUser'));
    }
}
