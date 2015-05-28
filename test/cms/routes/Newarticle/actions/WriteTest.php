<?php
namespace test\cms\routes\Newarticle\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Newarticle\actions\Write
 */
class WriteTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $queueMock;
    protected $authMock;
    protected $articleMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Newarticle\views\NewForm';
        $queueClass = '\cms\repositories\ArticleQueue';
        $authInterface = '\rakelley\jhframe\interfaces\services\IAuthService';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $articleClass = '\main\repositories\Article';
        $testedClass = '\cms\routes\Newarticle\actions\Write';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->queueMock = $this->getMockBuilder($queueClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->authMock = $this->getMock($authInterface);

        $validatorMock = $this->getMock($validatorInterface);

        $this->articleMock = $this->getMockBuilder($articleClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->testObj = new $testedClass($viewMock, $this->queueMock,
                                          $this->authMock, $validatorMock,
                                          $this->articleMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->queueMock, 'queue', $this->testObj);
        $this->assertAttributeEquals($this->authMock, 'auth', $this->testObj);
        $this->assertAttributeEquals($this->articleMock, 'article',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @covers ::<private>
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testProceed($hasPermission)
    {
        $input = ['content' => 'lorem ipsum', 'foo' => 'bar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $username = 'Test McTester';

        $this->authMock->expects($this->once())
                       ->method('getUser')
                       ->with($this->identicalTo('username'))
                       ->willReturn($username);
        $this->authMock->expects($this->once())
                       ->method('checkPermission')
                       ->with($this->isType('string'))
                       ->willReturn($hasPermission);

        $expectedRepo = ($hasPermission) ? $this->articleMock : $this->queueMock;

        $expectedRepo->expects($this->once())
                     ->method('addArticle')
                     ->with($this->isType('array'));

        $this->testObj->Proceed();
    }
}
