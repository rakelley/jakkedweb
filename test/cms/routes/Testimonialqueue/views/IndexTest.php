<?php
namespace test\cms\routes\Testimonialqueue\views;


/**
 * @coversDefaultClass \cms\routes\Testimonialqueue\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\TestimonialQueue';
        $testedClass = '\cms\routes\Testimonialqueue\views\Index';

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
