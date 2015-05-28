<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\Quotes
 */
class QuotesTest extends \test\helpers\cases\Base
{
    protected $modelMock;


    protected function setUp()
    {
        $modelClass = '\main\models\Quotes';
        $testedClass = '\main\repositories\Quotes';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $mockedMethods = [
            'pickRandomArrayElements',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->modelMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * Method called by setUp
     *
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'quoteModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $quotes = ['foo', 'bar', 'baz'];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('quotes'))
                        ->willReturn($quotes);

        $this->assertEquals($quotes, $this->testObj->getAll());
    }


    /**
     * @covers ::getRandom
     * @depends testGetAll
     */
    public function testGetRandom()
    {
        $count = 2;
        $quotes = [['quote' => 'foo'], ['quote' => 'bar'], ['quote' => 'baz']];
        $expected = array_column($quotes, 'quote');

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('quotes'))
                        ->willReturn($quotes);

        $this->testObj->expects($this->once())
                      ->method('pickRandomArrayElements')
                      ->with($this->identicalTo($quotes),
                             $this->identicalTo($count))
                      ->will($this->returnArgument(0));

        $this->assertEquals($expected, $this->testObj->getRandom($count));
    }

    /**
     * @covers ::getRandom
     * @depends testGetRandom
     */
    public function testGetRandomEmpty()
    {
        $count = 2;
        $quotes = null;
        $expected = null;

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('quotes'))
                        ->willReturn($quotes);

        $this->assertEquals($expected, $this->testObj->getRandom($count));
    }


    /**
     * @covers ::addQuote
     * @depends testConstruct
     */
    public function testAddQuote()
    {
        $quote = 'lorem ipsum';

        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($quote));

        $this->testObj->addQuote($quote);
    }


    /**
     * @covers ::deleteQuotes
     * @depends testConstruct
     */
    public function testDeleteQuotes()
    {
        $ids = [1, 3, 5, 7, 11];

        $this->modelMock->expects($this->once())
                        ->method('Delete')
                        ->with($this->identicalTo($ids));

        $this->testObj->deleteQuotes($ids);
    }
}
