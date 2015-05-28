<?php
namespace test\cms\routes\Trainers\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Trainers\views\PhotoForm
 */
class PhotoFormTest extends \test\helpers\cases\FormView
{
    protected $repoMock;


    protected function setUp()
    {
        $builderInterface =
            '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $repoClass = '\main\repositories\Trainers';
        $testedClass = '\cms\routes\Trainers\views\PhotoForm';

        $builderMock = $this->getMock($builderInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($builderMock, $this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'trainers',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $parameters = ['name' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $photo = 'foobar.jpg';
        $this->repoMock->expects($this->once())
                       ->method('getPhoto')
                       ->with($this->identicalTo($parameters['name']))
                       ->willReturn($photo);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($photo, 'data', $this->testObj);
    }


    /**
     * @covers ::fillCurrent
     * @depends testFetchData
     */
    public function testFillCurrent()
    {
        $withoutData = Utility::callMethod($this->testObj, 'fillCurrent');
        $this->assertEquals($withoutData, '');

        $this->testFetchData();
        $withData = Utility::callMethod($this->testObj, 'fillCurrent');
        $this->assertTrue(strlen($withData) > 1);
    }
}
