<?php
namespace test\cms\models;

/**
 * @coversDefaultClass \cms\models\AccountRecovery
 */
class AccountRecoveryTest extends \test\helpers\cases\Model
{
    protected $testedClass = '\cms\models\AccountRecovery';
    protected $traitedMethods = ['deleteByParameter', 'insertAll',
                                 'selectOneByParameter', 'setParameters'];


    /**
     * @covers ::Delete
     */
    public function testDelete()
    {
        $username = 'foobar';

        $this->testObj->expects($this->once())
                      ->method('setParameters')
                      ->with($this->identicalTo(['username' => $username]));
        $this->testObj->expects($this->once())
                      ->method('deleteByParameter');
        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->Delete($username);
    }


    /**
     * @covers ::Add
     * @depends testDelete
     */
    public function testAdd()
    {
        $input = ['username' => 'foobar', 'token' => 'abcde',
                  'expiration' => '2015-03-14 03:14:15'];

        $this->testObj->expects($this->once())
                      ->method('setParameters')
                      ->with($this->identicalTo(['username' =>
                                                 $input['username']]));
        $this->testObj->expects($this->once())
                      ->method('deleteByParameter');
        $this->testObj->expects($this->once())
                      ->method('resetProperties');

        $this->testObj->expects($this->once())
                      ->method('insertAll')
                      ->with($this->identicalTo($input));

        $this->testObj->Add($input);
    }


    /**
     * @covers ::getEntry
     */
    public function testGetEntry()
    {
        $row = ['username' => 'foobar', 'token' => 'abcde',
                'expiration' => '2015-03-14 03:14:15'];

        $this->testObj->expects($this->once())
                      ->method('selectOneByParameter')
                      ->willReturn($row);

        $this->assertEquals($row, $this->callMethod('getEntry'));
    }
}
