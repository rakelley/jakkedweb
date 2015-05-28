<?php
namespace test\cms\routes\Article\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Article\views\EditForm
 */
class EditFormTest extends \test\helpers\cases\FormView
{
    protected $articleMock;


    protected function setUp()
    {
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $articleClass = '\main\repositories\Article';
        $testedClass = '\cms\routes\Article\views\EditForm';

        $builderMock = $this->getMock($builderInterface);

        $this->articleMock = $this->getMockBuilder($articleClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $mockedMethods = [
            'standardConstructor',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$builderMock,
                                                    $this->articleMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    public function articleProvider()
    {
        return [
            [//normal case
                ['foo' => 'bar', 'baz' => 'bat'],
                ['lorem', 'ipsum'],
            ],
            [//article not found
                null,
                ['lorem', 'ipsum'],
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
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider articleProvider
     */
    public function testFetchData($article, $authors)
    {
        $parameters = ['id' => 1234];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->articleMock->expects($this->once())
                          ->method('getArticle')
                          ->with($this->identicalTo($parameters['id']))
                          ->willReturn($article);
        $this->articleMock->method('getAuthors')
                          ->willReturn($authors);

        if (!$article) {
            $this->setExpectedException('\DomainException');
        }
        $this->testObj->fetchData();

        if ($article) {
            $this->assertEquals(
                array_merge($article, ['authors' => $authors]),
                $this->readAttribute($this->testObj, 'data')
            );
        }
    }
}
