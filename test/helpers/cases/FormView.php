<?php
namespace test\helpers\cases;

/**
 * TestCase covering boilerplate FormView implementation
 */
abstract class FormView extends Base
{

    protected function setUp()
    {
        $mockedMethods = [
            'standardConstructor',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($this->testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @coversNothing
     */
    public function testProperties()
    {
        $this->assertInternalType(
            'array',
            $this->readAttribute($this->testObj, 'fields')
        );
        $this->assertInternalType(
            'array',
            $this->readAttribute($this->testObj, 'attributes')
        );
        $this->assertThat(
            $this->readAttribute($this->testObj, 'title'),
            $this->logicalOr(
                $this->isNull(),
                $this->isType('string'),
                $this->isType('array')
            )
        );
    }
}
