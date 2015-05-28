<?php
namespace test\helpers\cases;

/**
 * TestCase covering FormView with own ::constructView implementation
 */
abstract class FormViewCustomContent extends FormView
{
    use \test\helpers\traits\TestsConstructView;


    /**
     * @covers ::constructView
     */
    public function testConstructView()
    {
        $standard = 'lorem ipsum';

        $this->testObj->expects($this->once())
                      ->method('standardConstructor')
                      ->with($this->logicalOr($this->isType('string'),
                                              $this->isNull()))
                      ->willReturn($standard);

        $this->standardConstructViewTest();
    }
}
