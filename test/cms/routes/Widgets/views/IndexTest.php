<?php
namespace test\cms\routes\Widgets\views;

/**
 * @coversDefaultClass \cms\routes\Widgets\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Widgets\views\Index';


    /**
     * @covers ::constructView
     * @covers ::<private>
     */
    public function testConstructView()
    {
        $this->standardConstructViewTest();
    }
}
