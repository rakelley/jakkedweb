<?php
namespace test\cms\repositories;

/**
 * @coversDefaultClass \cms\repositories\ArticleQueue
 */
class ArticleQueueTest extends \test\helpers\cases\Base
{
    protected $modelMock;
    protected $filterMock;
    protected $userMock;


    protected function setUp()
    {
        $modelClass = '\cms\models\ArticleQueue';
        $filterInterface = '\rakelley\jhframe\interfaces\services\IFilter';
        $userClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\repositories\ArticleQueue';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->filterMock = $this->getMock($filterInterface);

        $this->userMock = $this->getMockBuilder($userClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->modelMock, $this->filterMock,
                                          $this->userMock);
    }


    /**
     * Uses parent constructor with modified dependency, just need to ensure
     * no exceptions thrown
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertTrue(true);
    }
}
