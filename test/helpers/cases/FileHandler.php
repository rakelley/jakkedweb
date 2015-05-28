<?php
namespace test\helpers\cases;


abstract class FileHandler extends Base
{

    protected function setUp()
    {
        $this->testObj = $this->getMockBuilder($this->testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods(null)
                              ->getMock();
    }


    /**
     * @coversNothing
     */
    public function testProperties()
    {
        $this->assertInternalType(
            'int',
            $this->readAttribute($this->testObj, 'maxFileSize')
        );
        $this->assertInternalType(
            'string',
            $this->readAttribute($this->testObj, 'relativePath')
        );
        $this->assertInternalType(
            'array',
            $this->readAttribute($this->testObj, 'validTypes')
        );
    }
}
