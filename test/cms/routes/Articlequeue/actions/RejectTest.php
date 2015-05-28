<?php
namespace test\cms\routes\Articlequeue\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Articlequeue\actions\Reject
 */
class RejectTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Articlequeue\views\DeleteForm';
        $repoClass = '\cms\repositories\ArticleQueue';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $testedClass = '\cms\routes\Articlequeue\actions\Reject';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->testObj = new $testedClass($viewMock, $this->repoMock,
                                          $validatorMock);
    }


    public function validationResultProvider()
    {
        return [
            [//article exists
                ['lorem', 'ipsum'],
            ],
            [//article doesn't exist
                null,
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'queue', $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['id' => 1234];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('deleteArticle')
                       ->with($this->identicalTo($input['id']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider validationResultProvider
     */
    public function testValidateInput($result)
    {
        $input = ['id' => 1234];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getArticle')
                       ->with($this->identicalTo($input['id']))
                       ->willReturn($result);

        if (!$result) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
