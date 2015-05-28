<?php
namespace test\cms\routes\Account;

/**
 * @coversDefaultClass \cms\routes\Account\Account
 */
class AccountTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\cms\routes\Account\Account';


    protected function setUp()
    {
        $mockedMethods = [
            'standardView',//implemented by parent
            'standardAction',//implemented by parent
            'getUserProperty',//trait implemented
            'getLocator',//trait implemented
            'routeAuth',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($this->testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();

        $this->createRoutedMethodsList();
    }


    /**
     * @covers ::Index
     */
    public function testIndex()
    {
        $key = 'username';
        $value = 'foobar';

        $this->assertContains('index', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('getUserProperty')
                      ->with($this->identicalTo($key))
                      ->willReturn($value);
        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->identicalTo([$key => $value]),
                             $this->isFalse());

        $this->testObj->Index();
    }


    /**
     * @covers ::Create
     */
    public function testCreate()
    {
        $this->assertContains('create', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Create();
    }


    /**
     * @covers ::Delete
     */
    public function testDelete()
    {
        $key = 'username';
        $value = 'foobar';

        $this->assertContains('delete', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('getUserProperty')
                      ->with($this->identicalTo($key))
                      ->willReturn($value);
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'),
                             $this->identicalTo([$key => $value]));

        $this->testObj->Delete();
    }


    /**
     * @covers ::setPassword
     */
    public function testSetPassword()
    {
        $key = 'username';
        $value = 'foobar';

        $this->assertContains('setpassword', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('getUserProperty')
                      ->with($this->identicalTo($key))
                      ->willReturn($value);
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'),
                             $this->identicalTo([$key => $value]));

        $this->testObj->setPassword();
    }


    /**
     * @covers ::setPhoto
     */
    public function testSetPhoto()
    {
        $key = 'username';
        $value = 'foobar';

        $this->assertContains('setphoto', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('getUserProperty')
                      ->with($this->identicalTo($key))
                      ->willReturn($value);
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'),
                             $this->identicalTo([$key => $value]));

        $this->testObj->setPhoto();
    }


    /**
     * @covers ::setProfile
     */
    public function testSetProfile()
    {
        $key = 'username';
        $value = 'foobar';

        $this->assertContains('setprofile', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('getUserProperty')
                      ->with($this->identicalTo($key))
                      ->willReturn($value);
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'),
                             $this->identicalTo([$key => $value]));

        $this->testObj->setProfile();
    }
}
