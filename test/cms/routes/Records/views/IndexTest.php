<?php
namespace test\cms\routes\Records\views;

/**
 * @coversDefaultClass \cms\routes\Records\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Records\views\Index';


    /**
     * @covers ::constructView
     * @covers ::<private>
     */
    public function testConstructView()
    {
        $this->standardConstructViewTest();
    }
}
