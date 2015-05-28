<?php
namespace test\main\routes\Records\views;

/**
 * @coversDefaultClass \main\routes\Records\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\main\routes\Records\views\Index';


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeNotEmpty('metaRoute', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     */
    public function testConstructView()
    {
        $this->standardConstructViewTest();
    }
}
