<?php
namespace test\main\routes\Article;

/**
 * @coversDefaultClass \main\routes\Article\Article
 */
class ArticleTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Article\Article';


    /**
     * @covers ::Article
     */
    public function testArticle()
    {
        $this->assertContains('article', $this->routedMethods);

        $id = 1234;

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->identicalTo(['id' => $id]));

        $this->testObj->Article($id);
    }
}
