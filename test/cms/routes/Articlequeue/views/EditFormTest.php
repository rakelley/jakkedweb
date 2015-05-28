<?php
namespace test\cms\routes\Articlequeue\views;

/**
 * @coversDefaultClass \cms\routes\Articlequeue\views\EditForm
 */
class EditFormTest extends \test\helpers\cases\FormView
{

    protected function setUp()
    {
        $queueClass = '\cms\repositories\ArticleQueue';
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $testedClass = '\cms\routes\Articlequeue\views\EditForm';

        $queueMock = $this->getMockBuilder($queueClass)
                          ->disableOriginalConstructor()
                          ->getMock();

        $builderMock = $this->getMock($builderInterface);

        $mockedMethods = [
            'standardConstructor',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$queueMock, $builderMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::__construct
     * @covers ::<private>
     */
    public function testConstruct()
    {
        $fields = $this->readAttribute($this->testObj, 'fields');
        $this->assertTrue(!in_array('date', array_keys($fields)));
    }
}
