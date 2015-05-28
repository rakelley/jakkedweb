<?php
namespace test\main\routes\Gallery\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Gallery\views\Shelters
 */
class SheltersTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Animals';
        $testedClass = '\main\routes\Gallery\views\Shelters';

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
        $this->assertAttributeEquals($this->repoMock, 'animals',
                                     $this->testObj);
        $this->assertAttributeNotEmpty('galleryClass', $this->testObj);
        $this->assertAttributeNotEmpty('galleryName', $this->testObj);
        $this->assertAttributeNotEmpty('metaRoute', $this->testObj);
    }


    /**
     * @covers ::getPhotoData
     * @depends testConstruct
     */
    public function testGetPhotoData()
    {
        $photos = ['lorem', 'ipsum'];

        $this->repoMock->expects($this->once())
                       ->method('getAnimals')
                       ->willReturn($photos);

        $this->assertEquals(
            $photos,
            Utility::callMethod($this->testObj, 'getPhotoData')
        );
    }


    /**
     * @covers ::getHeading
     */
    public function testGetHeading()
    {
        $heading = Utility::callMethod($this->testObj, 'getHeading');

        $this->assertArrayHasKey('title', $heading);
        $this->assertArrayHasKey('description', $heading);
        $this->assertInternalType('string', $heading['title']);
        $this->assertInternalType('string', $heading['description']);
    }


    /**
     * @covers ::wrapItem
     */
    public function testWrapItem()
    {
        $animal = [
            'number' => 1234,
            'name' => 'foobar',
            'photo' => 'foobar.jpg',
        ];

        $result = Utility::callMethod($this->testObj, 'wrapItem', [$animal]);

        $this->assertTrue(strlen($result) > 1);
    }
}
