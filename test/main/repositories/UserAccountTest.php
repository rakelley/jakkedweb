<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\UserAccount
 */
class UserAccountTest extends \test\helpers\cases\Base
{
    protected $imageMock;
    protected $usersMock;
    protected $rolesMock;
    protected $cryptoMock;


    protected function setUp()
    {
        $imageClass = '\main\UserImageHandler';
        $usersClass = '\main\models\Users';
        $rolesClass = '\main\models\UserRoles';
        $cryptoInterface = '\rakelley\jhframe\interfaces\services\ICrypto';
        $testedClass = '\main\repositories\UserAccount';

        $this->imageMock = $this->getMockBuilder($imageClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->usersMock = $this->getMockBuilder($usersClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->rolesMock = $this->getMockBuilder($rolesClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->cryptoMock = $this->getMock($cryptoInterface);

        $this->testObj = new $testedClass($this->imageMock, $this->usersMock,
                                          $this->rolesMock, $this->cryptoMock);
    }


    public function passwordValidationCaseProvider()
    {
        return [
            [//matches
                'abcde', true, false
            ],
            [//matches but needs updating
                'abcde', true, true
            ],
            [//doesn't match
                'abcde', false, false
            ],
            [//user not found
                null, false, false
            ],
        ];
    }


    public function photoProvider()
    {
        return [
            [//with photo
                'foobar', 'bazbat', 'foobar.jpg'
            ],
            [//without photo
                'lorem', 'ipsum', null
            ],
        ];
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->imageMock, 'photoHandler',
                                     $this->testObj);
        $this->assertAttributeEquals($this->usersMock, 'userModel',
                                     $this->testObj);
        $this->assertAttributeEquals($this->rolesMock, 'rolesModel',
                                     $this->testObj);
        $this->assertAttributeEquals($this->cryptoMock, 'crypto',
                                     $this->testObj);
    }


    /**
     * @covers ::getAdmins
     * @depends testConstruct
     */
    public function testGetAdmins()
    {
        $admins = ['lorem', 'ipsum'];

        $this->rolesMock->expects($this->once())
                        ->method('getUsersByPermission')
                        ->with($this->identicalTo('editusers'))
                        ->willReturn($admins);

        $this->assertEquals($admins, $this->testObj->getAdmins());
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $users = ['lorem', 'ipsum'];

        $this->usersMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('allusers'))
                        ->willReturn($users);

        $this->assertEquals($users, $this->testObj->getAll());
    }


    /**
     * @covers ::getAuthors
     * @depends testConstruct
     */
    public function testGetAuthors()
    {
        $authors = ['lorem', 'ipsum'];

        $this->rolesMock->expects($this->once())
                        ->method('getUsersByPermission')
                        ->with($this->identicalTo('writearticle'))
                        ->willReturn($authors);

        $this->assertEquals($authors, $this->testObj->getAuthors());
    }


    /**
     * @covers ::userExists
     * @covers ::userGet
     * @depends testConstruct
     */
    public function testUserExists()
    {
        $username = 'foobar';
        $userA = ['lorem', 'ipsum'];
        $userB = null;

        $this->usersMock->expects($this->exactly(2))
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->at(1))
                        ->method('__get')
                        ->with($this->identicalTo('user'))
                        ->willReturn($userA);
        $this->usersMock->expects($this->at(3))
                        ->method('__get')
                        ->with($this->identicalTo('user'))
                        ->willReturn($userB);

        $this->assertTrue($this->testObj->userExists($username));
        $this->assertFalse($this->testObj->userExists($username));
    }


    /**
     * @covers ::addUser
     * @depends testConstruct
     */
    public function testAddUser()
    {
        $input = ['lorem' => 'ipsum', 'password' => 'foobar'];

        $this->cryptoMock->expects($this->once())
                         ->method('hashString')
                         ->with($this->identicalTo($input['password']))
                         ->will($this->returnArgument(0));

        $this->usersMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($input));

        $this->testObj->addUser($input);
    }


    /**
     * @covers ::getFullname
     * @covers ::userGet
     * @depends testConstruct
     */
    public function testGetFullname()
    {
        $username = 'foobar';
        $fullname = 'bazbat';

        $this->usersMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('fullname'))
                        ->willReturn($fullname);

        $this->assertEquals($fullname, $this->testObj->getFullname($username));
    }


    /**
     * @covers ::setFullname
     * @covers ::userSet
     * @depends testConstruct
     */
    public function testSetFullname()
    {
        $username = 'foobar';
        $fullname = 'bazbat';

        $this->usersMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('fullname'),
                               $this->identicalTo($fullname));

        $this->testObj->setFullname($username, $fullname);
    }


    /**
     * @covers ::getLastLogin
     * @covers ::userGet
     * @depends testConstruct
     */
    public function testGetLastLogin()
    {
        $username = 'foobar';
        $time = '03-14-15 00:00:00';

        $this->usersMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('lastlogin'))
                        ->willReturn($time);

        $this->assertEquals($time, $this->testObj->getLastLogin($username));
    }


    /**
     * @covers ::setLastLogin
     * @covers ::userSet
     * @depends testConstruct
     */
    public function testSetLastLogin()
    {
        $username = 'foobar';
        $time = '03-14-15 00:00:00';

        $this->usersMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('lastlogin'),
                               $this->identicalTo($time));

        $this->testObj->setLastLogin($username, $time);
    }


    /**
     * @covers ::setPassword
     * @covers ::userSet
     * @depends testConstruct
     */
    public function testSetPassword()
    {
        $username = 'foobar';
        $password = 'password';
        $hash = 'abcde';

        $this->cryptoMock->expects($this->once())
                         ->method('hashString')
                         ->with($this->identicalTo($password))
                         ->willReturn($hash);

        $this->usersMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('password'),
                               $this->identicalTo($hash));

        $this->testObj->setPassword($username, $password);
    }


    /**
     * @covers ::validatePassword
     * @depends testConstruct
     * @dataProvider passwordValidationCaseProvider
     */
    public function testValidatePassword($hash, $valid, $needsUpdating)
    {
        $username = 'foobar';
        $password = 'password';

        $this->usersMock->expects($this->atLeastOnce())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('password'))
                        ->willReturn($hash);

        if ($hash) {
            $this->cryptoMock->expects($this->once())
                             ->method('compareHash')
                             ->with($this->identicalTo($password),
                                    $this->identicalTo($hash))
                             ->willReturn($valid);

            if ($valid) {
                $this->cryptoMock->expects($this->once())
                                 ->method('hashNeedsUpdating')
                                 ->with($this->identicalTo($hash))
                                 ->willReturn($needsUpdating);

                if ($needsUpdating) {
                    $secondHash = 'loremipsum';
                    $this->cryptoMock->expects($this->once())
                                     ->method('hashString')
                                     ->with($this->identicalTo($password))
                                     ->willReturn($secondHash);

                    $this->usersMock->expects($this->once())
                                    ->method('__set')
                                    ->with($this->identicalTo('password'),
                                           $this->identicalTo($secondHash));
                }
            }
        }

        $this->assertEquals(
            $valid,
            $this->testObj->validatePassword($username, $password)
        );
    }


    /**
     * @covers ::getPermissions
     * @depends testConstruct
     */
    public function testGetPermissions()
    {
        $username = 'foobar';
        $permissions = ['lorem', 'ipsum'];

        $this->rolesMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->rolesMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('permissions'))
                        ->willReturn($permissions);

        $this->assertEquals(
            $permissions,
            $this->testObj->getPermissions($username)
        );
    }


    /**
     * @covers ::getPhoto
     * @covers ::getPhotoKey
     * @depends testGetFullname
     * @dataProvider photoProvider
     */
    public function testGetPhoto($username, $fullname, $photo)
    {
        $expected = ($photo) ?: null;

        $this->usersMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('fullname'))
                        ->willReturn($fullname);

        $this->imageMock->expects($this->once())
                        ->method('Read')
                        ->with($this->identicalTo($fullname))
                        ->willReturn($photo);
        if ($expected) {
            $this->imageMock->expects($this->once())
                            ->method('makeRelative')
                            ->with($this->identicalTo($photo))
                            ->will($this->returnArgument(0));
        }

        $this->assertEquals($expected, $this->testObj->getPhoto($username));
    }


    /**
     * @covers ::setPhoto
     * @covers ::getPhotoKey
     * @depends testGetFullname
     * @dataProvider photoProvider
     */
    public function testSetPhoto($username, $fullname, $photo)
    {
        $this->usersMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('fullname'))
                        ->willReturn($fullname);

        if ($photo) {
            $this->imageMock->expects($this->once())
                            ->method('Write')
                            ->with($this->identicalTo($fullname),
                                   $this->identicalTo($photo));
        } else {
            $this->imageMock->expects($this->once())
                            ->method('Delete')
                            ->with($this->identicalTo($fullname));
        }

        $this->testObj->setPhoto($username, $photo);
    }


    /**
     * @covers ::validatePhoto
     * @depends testConstruct
     */
    public function testValidatePhoto()
    {
        $photo = ['tmp_name' => 'foobar', 'size' => 314159];
        $result = true;

        $this->imageMock->expects($this->once())
                        ->method('Validate')
                        ->with($this->identicalTo($photo))
                        ->willReturn($result);

        $this->assertEquals($result, $this->testObj->validatePhoto($photo));
    }


    /**
     * @covers ::deleteUser
     * @depends testSetPhoto
     */
    public function testDeleteUser()
    {
        $username = 'foobar';
        $fullname = 'bazbat';

        $this->usersMock->expects($this->exactly(2))
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('fullname'))
                        ->willReturn($fullname);
        $this->usersMock->expects($this->once())
                        ->method('Delete');

        $this->imageMock->expects($this->once())
                        ->method('Delete')
                        ->with($this->identicalTo($fullname));

        $this->testObj->deleteUser($username);
    }


    /**
     * @covers ::getProfile
     * @covers ::userGet
     * @depends testConstruct
     */
    public function testGetProfile()
    {
        $username = 'foobar';
        $profile = 'lorem ipsum';

        $this->usersMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('profile'))
                        ->willReturn($profile);

        $this->assertEquals($profile, $this->testObj->getProfile($username));
    }


    /**
     * @covers ::setProfile
     * @covers ::userSet
     * @depends testConstruct
     */
    public function testSetProfile()
    {
        $username = 'foobar';
        $profile = 'lorem ipsum';

        $this->usersMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->usersMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('profile'),
                               $this->identicalTo($profile));

        $this->testObj->setProfile($username, $profile);
    }


    /**
     * @covers ::getRoles
     * @depends testConstruct
     */
    public function testGetRoles()
    {
        $username = 'foobar';
        $roles = ['lorem, ipsum'];

        $this->rolesMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->rolesMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('roles'))
                        ->willReturn($roles);

        $this->assertEquals($roles, $this->testObj->getRoles($username));
    }


    /**
     * @covers ::setRoles
     * @depends testConstruct
     */
    public function testSetRoles()
    {
        $username = 'foobar';
        $roles = ['lorem, ipsum'];

        $this->rolesMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->rolesMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('roles'),
                               $this->identicalTo($roles));

        $this->testObj->setRoles($username, $roles);
    }
}
