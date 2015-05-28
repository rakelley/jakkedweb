<?php
namespace test\main\templates\Main;

/**
 * @coversDefaultClass \main\templates\Main\Head
 */
class HeadTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\main\templates\Main\Head';


    /**
     * @covers ::constructView
     */
    public function testConstructView()
    {
        $this->standardConstructViewTest();
    }
}
