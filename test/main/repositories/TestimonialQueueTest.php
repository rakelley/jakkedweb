<?php
namespace test\main\repositories;


/**
 * @coversDefaultClass \main\repositories\TestimonialQueue
 */
class TestimonialQueueTest extends \test\helpers\cases\Base
{
    protected $modelMock;


    protected function setUp()
    {
        $modelClass = '\main\models\TestimonialQueue';
        $testedClass = '\main\repositories\TestimonialQueue';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->modelMock);
    }


    /**
     * Method called by setUp
     *
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'testiModel',
                                     $this->testObj);
    }
}
