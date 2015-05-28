<?php
namespace test\cms\routes\Account\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Account\views\PhotoForm
 */
class PhotoFormTest extends \test\helpers\cases\FormView
{
    protected $accountMock;


    protected function setUp()
    {
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $accountClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Account\views\PhotoForm';

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


    public function photoProvider()
    {
        return [
            [//existing case
                'foobar.jpg'
            ],
            [//no existing case
                null
            ],
        ];
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
     * @dataProvider photoProvider
     */
    public function testFetchData($photo)
    {
        $parameters = ['username' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->accountMock->expects($this->once())
                          ->method('getPhoto')
                          ->with($this->identicalTo($parameters['username']))
                          ->willReturn($photo);

        $this->testObj->fetchData();
        $this->assertEquals(['photo' => $photo],
                            $this->readAttribute($this->testObj, 'data'));
    }


    /**
     * @covers ::fillCurrent
     * @depends testFetchData
     * @dataProvider photoProvider
     */
    public function testFillCurrent($photo)
    {
        $this->testFetchData($photo);

        $content = Utility::callMethod($this->testObj, 'fillCurrent');
        if (!$photo) {
            $this->assertEquals('', $content);
        } else {
            $this->assertTrue(strlen($content) > 1);
        }
    }
}
