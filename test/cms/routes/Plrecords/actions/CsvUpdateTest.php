<?php
namespace test\cms\routes\Plrecords\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Plrecords\actions\CsvUpdate
 */
class CsvUpdateTest extends \test\helpers\cases\Base
{
    protected $csvMock;


    protected function setUp()
    {
        $csvClass = '\cms\PlRecordCsvValidator';
        $viewClass = '\cms\routes\Plrecords\views\CsvForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $testedClass = '\cms\routes\Plrecords\actions\CsvUpdate';

        $this->csvMock = $this->getMockBuilder($csvClass)
                              ->disableOriginalConstructor()
                              ->getMock();

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->testObj = new $testedClass($this->csvMock, $viewMock,
                                          $validatorMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->csvMock, 'csvValidator',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $this->csvMock->expects($this->once())
                      ->method('pushValidated');

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     */
    public function testValidateInput()
    {
        $input = ['file' => ['tmp_name' => 'foobar']];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->csvMock->expects($this->once())
                      ->method('validateFile')
                      ->with($this->identicalTo($input['file']['tmp_name']));

        Utility::callMethod($this->testObj, 'validateInput');
    }
}