<?php
namespace test\cms\models;

/**
 * @coversDefaultClass \cms\models\CmsNav
 */
class CmsNavTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\cms\models\CmsNav';


    /**
     * @covers ::getNav
     * @dataProvider queryResultProvider
     */
    public function testGetNav($result)
    {
        $username = 'foobar';

        $this->dbMock->expects($this->once())
                     ->method('setQuery')
                     ->with($this->isType('string'))
                     ->will($this->returnSelf());

        $this->stmntMock->expects($this->once())
                        ->method('Bind')
                        ->with($this->identicalTo('username'),
                               $this->identicalTo(['username' => $username]))
                        ->will($this->returnSelf());
        $this->stmntMock->expects($this->once())
                        ->method('FetchAll')
                        ->willReturn($result);

        $expected = ($result) ?: null;
        $this->assertEquals($expected, $this->testObj->getNav($username));
    }
}
