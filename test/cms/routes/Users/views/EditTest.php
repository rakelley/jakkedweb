<?php
namespace test\cms\routes\Users\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Users\views\Edit
 */
class EditTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider,
        \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Users\views\Edit';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'user', $this->testObj);
    }



    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $parameters = ['username' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $photo = 'foobar.jpg';
        $profile = 'lorem ipsum';
        $expected = ['photo' => $photo, 'profile' => $profile];

        $this->repoMock->expects($this->once())
                       ->method('getPhoto')
                       ->with($this->identicalTo($parameters['username']))
                       ->willReturn($photo);
        $this->repoMock->expects($this->once())
                       ->method('getProfile')
                       ->with($this->identicalTo($parameters['username']))
                       ->willReturn($profile);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($expected, 'data', $this->testObj);
    }


    /**
     * @covers ::getSubViewList
     */
    public function testGetSubViewList()
    {
        $this->standardGetSubViewListTest();
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @depends testGetSubViewList
     * @dataProvider booleanCaseProvider
     */
    public function testConstructView($withData)
    {
        $parameters = ['username' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        if ($withData) {
            $this->testFetchData();
        }
        $this->mockSubViews();

        $this->standardConstructViewTest();
    }
}
