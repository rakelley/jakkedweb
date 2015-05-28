<?php
namespace test\main\routes\Flat;

/**
 * @coversDefaultClass \main\routes\Flat\Flat
 */
class FlatTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Flat\Flat';


    /**
     * The flatView method is trait implemented, we only need to ensure it's in
     * defined routes
     * @coversNothing
     */
    public function testRoutes()
    {
        $this->assertContains('flatView', $this->routedMethods);
    }
}
