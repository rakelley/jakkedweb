<?php
namespace test\cms\routes\Quotes\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Quotes\views\DeleteForm
 */
class DeleteFormTest extends \test\helpers\cases\FormView
{
    protected $repoMock;


    protected function setUp()
    {
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $repoClass = '\main\repositories\Quotes';
        $testedClass = '\cms\routes\Quotes\views\DeleteForm';

        $builderMock = $this->getMock($builderInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($builderMock, $this->repoMock);
    }


    public function quotesProvider()
    {
        return [
            [
                [
                    ['id' => 001, 'quote' => 'lorem ipsum'],
                    ['id' => 002, 'quote' => 'dolor'],
                    ['id' => 003, 'quote' => 'sit amet'],
                ]
            ]
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'quotes', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider quotesProvider
     */
    public function testFetchData($quotes)
    {
        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($quotes);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($quotes, 'data', $this->testObj);
    }


    /**
     * @covers ::fillQuotes
     * @depends testFetchData
     * @dataProvider quotesProvider
     */
    public function testFillQuotes($quotes)
    {
        $this->testFetchData($quotes);

        $result = Utility::callMethod($this->testObj, 'fillQuotes');
        $this->assertTrue(strlen($result) > 1);
    }
}
