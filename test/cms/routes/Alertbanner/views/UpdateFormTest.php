<?php
namespace test\cms\routes\Alertbanner\views;

/**
 * @coversDefaultClass \cms\routes\Alertbanner\views\UpdateForm
 */
class UpdateFormTest extends \test\helpers\cases\FormView
{

    protected function setUp()
    {
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $repoClass = '\main\repositories\Alertbanner';
        $testedClass = '\cms\routes\Alertbanner\views\UpdateForm';

        $builderMock = $this->getMock($builderInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedMethods = [
            'standardConstructor',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$builderMock,
                                                    $this->repoMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'banner', $this->testObj);
    }


    /**
     * @covers ::fetchData
     */
    public function testFetchData()
    {
        $data = ['foo' => 'bar'];

        $this->repoMock->expects($this->once())
                       ->method('getBanner')
                       ->willReturn($data);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($data, 'data', $this->testObj);
    }
}
