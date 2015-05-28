<?php
namespace test\cms\routes\Auth\views;

/**
 * @coversDefaultClass \cms\routes\Auth\views\Logout
 */
class LogoutTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Auth\views\Logout';


    /**
     * @covers ::constructView
     */
    public function testConstructView()
    {
        $this->standardConstructViewTest();
    }
}
