<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\Testimonials
 */
class TestimonialsTest extends \test\helpers\cases\Base
{
    protected $modelMock;


    protected function setUp()
    {
        $modelClass = '\main\models\Testimonials';
        $testedClass = '\main\repositories\Testimonials';

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
        $this->assertAttributeEquals($this->modelMock, 'testiModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $testimonials = ['lorem', 'ispum'];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('testimonials'))
                        ->willReturn($testimonials);

        $this->assertEquals($testimonials, $this->testObj->getAll());
    }


    /**
     * @covers ::getRandom
     * @depends testGetAll
     */
    public function testGetRandom()
    {
        $count = 2;
        $testimonials = ['content' => 'lorem', 'content' => 'ipsum'];
        $expected = array_column($testimonials, 'content');

        $this->modelMock->method('__get')
                        ->with($this->identicalTo('testimonials'))
                        ->willReturn($testimonials);

        $this->testObj->expects($this->once())
                      ->method('pickRandomArrayElements')
                      ->with($this->identicalTo($testimonials),
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
        $testimonials = null;
        $expected = null;

        $this->modelMock->method('__get')
                        ->with($this->identicalTo('testimonials'))
                        ->willReturn($testimonials);

        $this->assertEquals($expected, $this->testObj->getRandom($count));
    }


    /**
     * @covers ::getTestimonial
     * @depends testConstruct
     */
    public function testGetTestimonial()
    {
        $id = 3;
        $testimonial = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['id' => $id]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('testimonial'))
                        ->willReturn($testimonial);

        $this->assertEquals($testimonial, $this->testObj->getTestimonial($id));
    }


    /**
     * @covers ::addTestimonial
     * @depends testConstruct
     */
    public function testAddTestimonial()
    {
        $testimonial = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($testimonial));

        $this->testObj->addTestimonial($testimonial);
    }


    /**
     * @covers ::deleteTestimonial
     * @depends testConstruct
     */
    public function testDeleteTestimonial()
    {
        $id = 3;

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['id' => $id]));
        $this->modelMock->expects($this->once())
                        ->method('__unset')
                        ->with($this->identicalTo('testimonial'));

        $this->testObj->deleteTestimonial($id);
    }
}
