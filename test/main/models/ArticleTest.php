<?php
namespace test\main\models;

/**
 * @coversDefaultClass \main\models\Article
 */
class ArticleTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\main\models\Article';
    protected $traitedMethods = ['deleteByParameter', 'getCount',
                                 'insertAutoPrimary', 'setParameters',
                                 'updateByPrimary'];


    public function articlesCaseProvider()
    {
        return [
            [//no parameters, result
                null,
                ['lorem', 'ipsum'],
            ],
            [//no parameters, no result
                null,
                [],
            ],
            [//correct parameters, result
                ['pointer' => 10, 'results' => 20],
                ['lorem', 'ipsum'],
            ],
            [//random parameters, result
                ['foo' => 'bar', 'baz' => 'bat'],
                ['lorem', 'ipsum'],
            ],
        ];
    }


    /**
     * @covers ::revertAuthor
     */
    public function testRevertAuthor()
    {
        $keyName = 'author';
        $key = 'foobar';

        $this->dbMock->expects($this->once())
                     ->method('setQuery')
                     ->with($this->isType('string'))
                     ->will($this->returnSelf());

        $this->whereMock->expects($this->once())
                        ->method('Equals')
                        ->with($this->identicalTo($keyName))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo($keyName),
                               $this->identicalTo([$keyName => $key]))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('Execute');

        $this->testObj->revertAuthor($key);
    }


    /**
     * @covers ::Add
     */
    public function testAdd()
    {
        $input = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('insertAutoPrimary')
                      ->with($this->identicalTo($input));
        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->Add($input);
    }


    /**
     * @covers ::getArticle
     * @dataProvider queryResultProvider
     */
    public function testGetArticle($result)
    {
        $table = $this->readAttribute($this->testObj, 'table');
        $primary = $this->readAttribute($this->testObj, 'primary');

        $parameters = ['lorem', 'ipsum'];
        $this->setUpParameters($parameters);

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table),
                            $this->arrayHasKey('select'))
                     ->will($this->returnSelf());

        $this->joinMock->expects($this->once())
                       ->method('On')
                       ->with($this->isType('string'), $this->isType('string'))
                       ->willReturn($this->dbMock);

        $this->whereMock->expects($this->once())
                        ->method('Equals')
                        ->with($this->identicalTo($primary))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo($primary),
                               $this->identicalTo($parameters))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('Fetch')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->callMethod('getArticle'));
    }


    /**
     * @covers ::setArticle
     */
    public function testSetArticle()
    {
        $input = ['lorem', 'ipsum'];

        $this->testObj->expects($this->once())
                      ->method('updateByPrimary')
                      ->with($this->identicalTo($input));

        $this->callMethod('setArticle', [$input]);
    }


    /**
     * @covers ::unsetArticle
     */
    public function testUnsetArticle()
    {
        $this->testObj->expects($this->once())
                      ->method('deleteByParameter');

        $this->callMethod('unsetArticle');
    }


    /**
     * @covers ::getArticles
     * @dataProvider articlesCaseProvider
     */
    public function testGetArticles($parameters, $result)
    {
        if ($parameters) {
            $this->setUpParameters($parameters);

            if (isset($parameters['pointer']) &&
                isset($parameters['results'])
            ) {
                $limit = [$parameters['pointer'], $parameters['results']];
                $this->dbMock->expects($this->once())
                             ->method('addLimit')
                             ->with($this->identicalTo($limit))
                             ->will($this->returnSelf());
            }
        }

        $primary = $this->readAttribute($this->testObj, 'primary');
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table),
                            $this->arrayHasKey('select'))
                     ->will($this->returnSelf());
        $this->dbMock->expects($this->once())
                     ->method('stripTicks')
                     ->will($this->returnSelf());
        $this->dbMock->expects($this->once())
                     ->method('addOrder')
                     ->with($this->contains($primary))
                     ->will($this->returnSelf());

        $this->joinMock->expects($this->once())
                       ->method('On')
                       ->with($this->isType('string'), $this->isType('string'))
                       ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->callMethod('getArticles'));
    }


    /**
     * @covers ::getResults
     * @dataProvider queryResultProvider
     */
    public function testGetResults($result)
    {
        $parameters = ['query' => 'foo_bar'];
        $this->setUpParameters($parameters);

        $termCount = count(explode('_', $parameters['query']));
        $primary = $this->readAttribute($this->testObj, 'primary');
        $table = $this->readAttribute($this->testObj, 'table');

        $this->dbMock->expects($this->once())
                     ->method('newQuery')
                     ->with($this->identicalTo('select'),
                            $this->identicalTo($table),
                            $this->arrayHasKey('select'))
                     ->will($this->returnSelf());
        $this->dbMock->expects($this->once())
                     ->method('stripTicks')
                     ->will($this->returnSelf());
        $this->dbMock->expects($this->once())
                     ->method('addOrder')
                     ->with($this->contains($primary))
                     ->will($this->returnSelf());

        $this->joinMock->expects($this->once())
                       ->method('On')
                       ->with($this->isType('string'), $this->isType('string'))
                       ->willReturn($this->dbMock);

        $this->whereMock->expects($this->atLeastOnce())
                        ->method('Like')
                        ->with($this->isType('string'),
                               $this->countOf($termCount),
                               $this->isType('string'))
                        ->willReturn($this->dbMock);

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->countOf($termCount),
                               $this->countOf($termCount))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->callMethod('getResults'));
    }


    /**
     * @covers ::getResults
     */
    public function testGetResultsNoQuery()
    {
        $this->setExpectedException('\BadMethodCallException');

        $this->callMethod('getResults');
    }
}
