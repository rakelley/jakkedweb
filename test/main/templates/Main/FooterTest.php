<?php
namespace test\main\templates\Main;

/**
 * @coversDefaultClass \main\templates\Main\Footer
 */
class FooterTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\main\templates\Main\Footer';


    /**
     * @covers ::constructView
     */
    public function testConstructView()
    {
        $this->standardConstructViewTest();
    }
}
