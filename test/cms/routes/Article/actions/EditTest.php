<?php
namespace test\cms\routes\Article\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Article\actions\Edit
 */
class EditTest extends \test\helpers\cases\Base
{
    protected $articleMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Article\views\EditForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $articleClass = '\main\repositories\Article';
        $testedClass = '\cms\routes\Article\actions\Edit';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->articleMock = $this->getMockBuilder($articleClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->testObj = new $testedClass($viewMock, $validatorMock,
                                          $this->articleMock);
    }


    public function validationCaseProvider()
    {
        return [
            [//valid
                ['lorem', 'ipsum'],
                'foobar',
                ['foobar', 'bazbat'],
            ],
            [//article not found
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
        $this->assertAttributeEquals($this->articleMock, 'article',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['lorem', 'ipsum'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->articleMock->expects($this->once())
                          ->method('setArticle')
                          ->with($this->identicalTo($input));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider validationCaseProvider
     */
    public function testValidateInput($article, $author, $authors)
    {
        $input = ['id' => 1234, 'author' => $author];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->articleMock->expects($this->once())
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
