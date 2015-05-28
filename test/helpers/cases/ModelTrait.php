<?php
namespace test\helpers\cases;

/**
 * TestCase for traits for Models
 */
class ModelTrait extends Base
{
    use \test\helpers\traits\MockDatabaseService;


    protected function setUp()
    {
        $mockedMethods = [
            'resetProperties',//parent class implemented
        ];
        $this->testObj = $this->getMockForTrait($this->testedClass, [], '',
                                                true, true, true,
                                                $mockedMethods);

        $this->setUpDbMock();
        $this->testObj->db = $this->dbMock;
    }
}
