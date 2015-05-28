<?php
namespace test\cms\routes\Files\views;

/**
 * @coversDefaultClass \cms\routes\Files\views\AddForm
 */
class AddFormTest extends \test\helpers\cases\FormView
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $builderInterface =
            '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $repoClass = '\main\repositories\Gallery';
        $testedClass = '\cms\routes\Files\views\AddForm';

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
        $this->assertAttributeEquals($this->repoMock, 'galleries',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $galleries = ['foo', 'bar', 'baz'];

        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($galleries);

        $this->testObj->fetchData();
        $this->assertAttributeEquals(['galleries' => $galleries], 'data',
                                     $this->testObj);
    }


    /**
     * @covers ::constructView
     */
    public function testConstructView()
    {
        $standard = 'lorem ipsum';

        $this->testObj->expects($this->once())
                      ->method('standardConstructor')
                      ->willReturn($standard);

        $this->standardConstructViewTest();
    }
}
