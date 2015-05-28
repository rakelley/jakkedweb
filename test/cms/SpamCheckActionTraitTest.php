<?php
namespace test\cms;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\SpamCheckActionTrait
 */
class SpamCheckActionTraitTest extends \test\helpers\cases\Base
{
    protected $configMock;


    protected function setUp()
    {
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $testedTrait = '\cms\SpamCheckActionTrait';

        $this->configMock = $this->getMock($configInterface);

        $this->testObj = $this->getMockForTrait($testedTrait);
        $this->testObj->method('getConfig')
                      ->willReturn($this->configMock);
    }


    public function validationCaseProvider()
    {
        return [
            [//passes and returns
                'foo,bar,baz',
                'foo',
                true,
                true
            ],
            [//passes no return
                'foo,bar,baz',
                'foo',
                false,
                true
            ],
            [//fails and returns
                'foo,bar,baz',
                'lorem',
                true,
                false
            ],
            [//passes and throws
                'foo,bar,baz',
                'lorem',
                false,
                false
            ],
        ];
    }


    /**
     * @covers ::validateSpamCheck
     * @dataProvider validationCaseProvider
     */
    public function testValidateSpamCheck($valid, $input, $return, $success)
    {
        $this->configMock->method('Get')
                         ->with($this->identicalTo('SECRETS'),
                                $this->identicalTo('HUMANVERIFY'))
                         ->willReturn($valid);

        if ($return) {
            $this->assertEquals(
                $success,
                Utility::callMethod($this->testObj, 'validateSpamCheck',
                                    [$input, $return])
            );
        } else {
            if (!$success) {
                $this->setExpectedException('\rakelley\jhframe\classes\InputException');
            }
            Utility::callMethod($this->testObj, 'validateSpamCheck',
                                    [$input, $return]);
        }   
    }
}
