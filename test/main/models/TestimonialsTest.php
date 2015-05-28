<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\Testimonials
 */
class TestimonialsTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\Testimonials';
    protected $traitedMethods = ['deleteByParameter', 'insertAutoPrimary',
                                 'selectAll', 'selectOneByParameter',
                                 'setParameters'];

    /**
     * @covers ::Add
     */
    public function testAdd()
    {
        $input = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('insertAutoPrimary')
                      ->with($this->identicalTo($input));
        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->Add($input);
    }


    /**
     * @covers ::getTestimonials
     */
    public function testGetTestimonials()
    {
        $result = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectAll')
                      ->willReturn($result);

        $this->assertEquals($result, $this->callMethod('getTestimonials'));
    }


    /**
     * @covers ::getTestimonial
     */
    public function testGetTestimonial()
    {
        $result = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('selectOneByParameter')
                      ->willReturn($result);

        $this->assertEquals($result, $this->callMethod('getTestimonial'));
    }


    /**
     * @covers ::unsetTestimonial
     */
    public function testUnsetTestimonial()
    {
        $this->testObj->expects($this->once())
                      ->method('deleteByParameter');

        $this->callMethod('unsetTestimonial');
    }
}
