<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\Quotes
 */
class QuotesTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\Quotes';
    protected $traitedMethods = ['deleteOnValues', 'insertAutoPrimary',
                                 'selectAll'];


    /**
     * @covers ::Add
     */
    public function testAdd()
    {
        $quote = 'lorem ipsum';

        $this->testObj->expects($this->once())
                      ->method('insertAutoPrimary')
                      ->with($this->identicalTo(['quote' => $quote]));

        $this->testObj->Add($quote);
    }


    /**
     * @covers ::Delete
     */
    public function testDelete()
    {
        $ids = [1, 4, 7];

        $this->testObj->expects($this->once())
                      ->method('deleteOnValues')
                      ->with($this->identicalTo($ids));

        $this->testObj->Delete($ids);
    }


    /**
     * @covers ::getQuotes
     */
    public function testGetQuotes()
    {
        $result = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectAll')
                      ->willReturn($result);

        $this->assertEquals($result, $this->callMethod('getQuotes'));
    }
}
