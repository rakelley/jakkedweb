<?php
namespace test\main\routes\Gallery;

/**
 * @coversDefaultClass \main\routes\Gallery\Gallery
 */
class GalleryTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Gallery\Gallery';


    /**
     * @covers ::Index
     */
    public function testIndex()
    {
        $this->assertContains('index', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'));

        $this->testObj->Index();
    }


    /**
     * @covers ::Shelters
     */
    public function testShelters()
    {
        $this->assertContains('shelters', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'));

        $this->testObj->Shelters();
    }


    /**
     * @covers ::Gallery
     */
    public function testGallery()
    {
        $gallery = 'foobar';

        $this->assertContains('gallery', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->identicalTo(['gallery' => $gallery]));

        $this->testObj->Gallery($gallery);
    }
}
