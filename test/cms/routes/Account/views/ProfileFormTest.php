<?php
namespace test\cms\routes\Account\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Account\views\ProfileForm
 */
class ProfileFormTest extends \test\helpers\cases\FormViewCustomContent
{
    protected $accountMock;


    protected function setUp()
    {
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $accountClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Account\views\ProfileForm';

        $builderMock = $this->getMock($builderInterface);

        $this->accountMock = $this->getMockBuilder($accountClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $mockedMethods = [
            'standardConstructor',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$builderMock,
                                                    $this->accountMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->accountMock, 'user',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $parameters = ['username' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $profile = 'lorem ipsum';

        $this->accountMock->expects($this->once())
                          ->method('getProfile')
                          ->with($this->identicalTo($parameters['username']))
                          ->willReturn($profile);

        $this->testObj->fetchData();
        $this->assertEquals(['profile' => $profile],
                            $this->readAttribute($this->testObj, 'data'));
    }
}
