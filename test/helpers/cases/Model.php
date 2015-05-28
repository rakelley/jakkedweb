<?php
namespace test\helpers\cases;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * TestCase for Models
 */
class Model extends Base
{
    use \test\helpers\traits\MockDatabaseService;

    /**
     * List of methods in tested model that are trait implemented and should be
     * mocked
     * @var array
     */
    protected $traitedMethods = [];


    protected function setUp()
    {
        $this->setUpDbMock();

        $commonMethods = [
            'resetProperties',//parent implemented
        ];
        $mockedMethods = array_merge($commonMethods, $this->traitedMethods);
        $this->testObj = $this->getMockBuilder($this->testedClass)
                              ->setConstructorArgs([$this->dbMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }

    protected function setUpParameters($parameters)
    {
        Utility::setProperties(['parameters' => $parameters], $this->testObj);
    }

    protected function callMethod($method, $args=[])
    {
        return Utility::callMethod($this->testObj, $method, $args);
    }


    /**
     * Generic data provider for tests which just need to check empty/non-empty
     * results
     */
    public function queryResultProvider()
    {
        return [
            [//result
                ['lorem', 'ipsum', 'dolor'],
            ],
            [//no result
                []
            ],
        ];
    }


    /**
     * @coversNothing
     */
    public function testAttributes()
    {
        $this->assertInternalType(
            'array',
            $this->readAttribute($this->testObj, 'columns')
        );
        $this->assertThat(
            $this->readAttribute($this->testObj, 'primary'),
            $this->logicalOr($this->isType('string'),
                             $this->isType('array'),
                             $this->isNull())
        );
        $this->assertInternalType(
            'string',
            $this->readAttribute($this->testObj, 'table')
        );
    }
}
