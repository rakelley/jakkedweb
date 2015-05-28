<?php
namespace test\cms\routes\Articlequeue\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Articlequeue\actions\Approve
 */
class ApproveTest extends \test\helpers\cases\Base
{
    protected $queueMock;
    protected $articleMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Articlequeue\views\EditForm';
        $queueClass = '\cms\repositories\ArticleQueue';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $articleClass = '\main\repositories\Article';
        $testedClass = '\cms\routes\Articlequeue\actions\Approve';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->queueMock = $this->getMockBuilder($queueClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->articleMock = $this->getMockBuilder($articleClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->testObj = new $testedClass($viewMock, $this->queueMock,
                                          $validatorMock, $this->articleMock);
    }


    public function validationCaseProvider()
    {
        return [
            [//valid
                ['lorem', 'ipsum'],
                'foobar',
                ['foobar', 'bazbat'],
            ],
            [//no article
                null,
                'foobar',
                ['foobar', 'bazbat'],
            ],
            [//invalid author
                ['lorem', 'ipsum'],
                'foobar',
                ['bazbat'],
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->queueMock, 'queue', $this->testObj);
        $this->assertAttributeEquals($this->articleMock, 'article',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['foo' => 'bar', 'id' => 1234];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->articleMock->expects($this->once())
                          ->method('addArticle')
                          ->with($this->identicalTo($input));

        $this->queueMock->expects($this->once())
                        ->method('deleteArticle')
                        ->with($this->identicalTo($input['id']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider validationCaseProvider
     */
    public function testValidateInput($article, $author, $authors)
    {
        $input = ['author' => $author, 'id' => 1234];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->queueMock->expects($this->once())
                        ->method('getArticle')
                        ->with($this->identicalTo($input['id']))
                        ->willReturn($article);

        $this->articleMock->method('getAuthors')
                        ->willReturn($authors);

        if (!$article || !in_array($author, $authors)) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
