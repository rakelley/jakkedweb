<?php
namespace test\cms\routes\Nav\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Nav\views\EditForm
 */
class EditFormTest extends \test\helpers\cases\FormViewCustomContent
{
    protected $repoMock;


    protected function setUp()
    {
        $builderInterface =
            '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $repoClass = '\main\repositories\Navigation';
        $testedClass = '\cms\routes\Nav\views\EditForm';

        $builderMock = $this->getMock($builderInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedMethods = [
            'standardConstructor',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$builderMock,
                                                    $this->repoMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    public function entryCaseProvider()
    {
        return [
            [//has parent
                ['foo' => 'bar', 'parent' => 'baz']
            ],
            [//no parent
                ['foo' => 'bar', 'parent' => null]
            ],
            [//no entry
                null
            ]
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'navEntries',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider entryCaseProvider
     */
    public function testFetchData($entry)
    {
        $route = 'foobar';
        Utility::setProperties(['parameters' => ['route' => $route]],
                               $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getEntry')
                       ->with($this->identicalTo($route))
                       ->willReturn($entry);

        if ($entry) {
            $parents = ['lorem', 'ipsum'];
            $this->repoMock->expects($this->once())
                           ->method('getParentList')
                           ->willReturn($parents);

            $this->testObj->fetchData();
            $this->assertAttributeEquals(
                ['entry' => $entry, 'parents' => $parents],
                'data',
                $this->testObj
            );
        } else {
            $this->setExpectedException('\DomainException');
            $this->testObj->fetchData();
        }
    }
}
