<?php
namespace test\cms\routes\Articlequeue\views;

/**
 * @coversDefaultClass \cms\routes\Articlequeue\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\cms\repositories\ArticleQueue';
        $testedClass = '\cms\routes\Articlequeue\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'queueRepo',
                                     $this->testObj);
        $this->assertAttributeNotEmpty('queueName', $this->testObj);
        $this->assertAttributeNotEmpty('queueController', $this->testObj);
    }
}
