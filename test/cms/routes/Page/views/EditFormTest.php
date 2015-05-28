<?php
namespace test\cms\routes\Page\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Page\views\EditForm
 */
class EditFormTest extends \test\helpers\cases\FormView
{
    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\cms\repositories\FlatPage';
        $builderInterface =
            '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $testedClass = '\cms\routes\Page\views\EditForm';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $builderMock = $this->getMock($builderInterface);

        $mockedMethods = [
            'standardConstructor',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->repoMock,
                                                    $builderMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'page', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $parameters = ['name' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $page = ['foo' => 'bar', 'baz' => 'bat'];

        $this->repoMock->expects($this->once())
                       ->method('getPage')
                       ->with($this->identicalTo($parameters['name']))
                       ->willReturn($page);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($page, 'data', $this->testObj);
    }
}
