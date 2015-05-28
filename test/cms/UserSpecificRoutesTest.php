<?php
namespace test\cms;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\UserSpecificRoutes
 */
class UserSpecificRoutesTest extends \test\helpers\cases\Base
{
    protected $authMock;


    protected function setUp()
    {
        $authInterface = '\rakelley\jhframe\interfaces\services\IAuthService';
        $locatorInterface =
            '\rakelley\jhframe\interfaces\services\IServiceLocator';
        $testedTrait = '\cms\UserSpecificRoutes';

        $this->authMock = $this->getMock($authInterface);

        $locatorMock = $this->getMock($locatorInterface);
        $locatorMock->method('Make')
                    ->with($this->identicalTo($authInterface))
                    ->willReturn($this->authMock);

        $mockedMethods = [
            'getLocator',//abstract
        ];
        $this->testObj = $this->getMockBuilder($testedTrait)
                              ->setMethods($mockedMethods)
                              ->getMockForTrait();
        $this->testObj->method('getLocator')
                      ->willReturn($locatorMock);
    }


    /**
     * @covers ::getUserProperty
     */
    public function testGetUserProperty()
    {
        $property = 'foo';
        $value = 'bar';

        $this->authMock->expects($this->once())
                       ->method('getUser')
                       ->with($this->identicalTo($property))
                       ->willReturn($value);

        $this->assertEquals(
            $value,
            Utility::callMethod($this->testObj, 'getUserProperty', [$property])
        );
    }
}
