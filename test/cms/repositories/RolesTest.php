<?php
namespace test\cms\repositories;

/**
 * @coversDefaultClass \cms\repositories\Roles
 */
class RolesTest extends \test\helpers\cases\Base
{
    protected $modelMock;


    protected function setUp()
    {
        $modelClass = '\cms\models\Roles';
        $testedClass = '\cms\repositories\Roles';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->modelMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'roles', $this->testObj);
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $roles = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('roles'))
                        ->willReturn($roles);

        $this->assertEquals($roles, $this->testObj->getAll());
    }


    /**
     * @covers ::getRelations
     * @depends testGetAll
     */
    public function testGetRelations()
    {
        $roles = [
            ['role' => 'foo', 'related' => ''],
            ['role' => 'bar', 'related' => 'foo,lorem'],
            ['role' => 'baz', 'related' => ''],
            ['role' => 'bat', 'related' => 'baz'],
            ['role' => 'lorem', 'related' => ''],
            ['role' => 'ipsum', 'related' => 'lorem'],
            ['role' => 'dolor', 'related' => 'foo'],
        ];
        $expected = [
            'foo' => ['bar', 'dolor'],
            'lorem' => ['bar', 'ipsum'],
            'baz' => ['bat'],
        ];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('roles'))
                        ->willReturn($roles);

        $this->assertEquals($expected, $this->testObj->getRelations());
    }
}
