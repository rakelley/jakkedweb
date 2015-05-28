<?php
namespace test\cms\routes\Trainers\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Trainers\views\EditForm
 */
class EditFormTest extends \test\helpers\cases\FormView
{
    protected $repoMock;


    protected function setUp()
    {
        $builderInterface =
            '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $repoClass = '\main\repositories\Trainers';
        $testedClass = '\cms\routes\Trainers\views\EditForm';

        $builderMock = $this->getMock($builderInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($builderMock, $this->repoMock);
    }


    public function trainerCaseProvider()
    {
        return [
            [//not found
                null,
            ],
            [//visible
                ['lorem' => 'ipsum', 'visible' => true],
            ],
            [//not visible
                ['lorem' => 'ipsum', 'visible' => false],
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'trainers',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider trainerCaseProvider
     */
    public function testFetchData($trainer)
    {
        $parameters = ['name' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getTrainer')
                       ->with($this->identicalTo($parameters['name']))
                       ->willReturn($trainer);

        if (!$trainer) {
            $this->setExpectedException('\DomainException');
        }
        $this->testObj->fetchData();

        if ($trainer) {
            $visible = $this->readAttribute($this->testObj, 'data')['visible'];
            $this->assertTrue(strlen($visible) > 1);
        }
    }
}
