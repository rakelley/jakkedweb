<?php
namespace test\main\routes\Gallery\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Gallery\views\Gallery
 */
class GalleryTest extends \test\helpers\cases\Base
{
    protected $testedClass = '\main\routes\Gallery\views\Gallery';


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeNotEmpty('galleryClass', $this->testObj);
    }


    /**
     * @covers ::setParameters
     */
    public function testSetParameters()
    {
        $parameters = ['gallery' => 'foobar'];

        $this->testObj->setParameters($parameters);

        $this->assertAttributeNotEmpty('galleryName', $this->testObj);
        $this->assertAttributeNotEmpty('metaRoute', $this->testObj);
    }


    /**
     * @covers ::getHeading
     * @depends testSetParameters
     */
    public function testGetHeading()
    {
        $this->testSetParameters();

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
        $item = [
            'original' => 'example.jpg',
            'thumb' => 'thumbs/example.jpg',
        ];
        $result = Utility::callMethod($this->testObj, 'wrapItem', [$item]);

        $this->assertInternalType('string', $result);
    }
}
