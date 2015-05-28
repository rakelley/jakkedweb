<?php
namespace test\cms\routes\Nav\views;

/**
 * @coversDefaultClass \cms\routes\Nav\views\AddForm
 */
class AddFormTest extends \test\helpers\cases\FormViewCustomContent
{
    protected $repoMock;


    protected function setUp()
    {
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $repoClass = '\main\repositories\Navigation';
        $testedClass = '\cms\routes\Nav\views\AddForm';

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
        $this->assertAttributeEquals($this->repoMock, 'navEntries',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $parents = ['lorem', 'ipsum'];

        $this->repoMock->expects($this->once())
                       ->method('getParentList')
                       ->willReturn($parents);

        $this->testObj->fetchData();
        $this->assertAttributeEquals(['parents' => $parents], 'data',
                                     $this->testObj);
    }
}
