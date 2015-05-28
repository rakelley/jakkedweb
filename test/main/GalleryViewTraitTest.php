<?php
namespace test\main;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\GalleryViewTrait
 */
class GalleryViewTraitTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $galleryMock;


    protected function setUp()
    {
        $galleryClass = '\main\repositories\Gallery';
        $locatorInterface =
            '\rakelley\jhframe\interfaces\services\IServiceLocator';
        $testedTrait = '\main\GalleryViewTrait';

        $this->galleryMock = $this->getMockBuilder($galleryClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $locatorMock = $this->getMock($locatorInterface);
        $locatorMock->method('Make')
                    ->with($this->identicalTo($galleryClass))
                    ->willReturn($this->galleryMock);

        $this->testObj = $this->getMockForTrait($testedTrait);
        $this->testObj->method('getLocator')
                      ->willReturn($locatorMock);
    }


    /**
     * @covers ::fetchData
     * @covers ::getNavData
     * @covers ::getPhotoData
     */
    public function testFetchData()
    {
        $name = 'foobar';
        $nav = ['nav', 'data'];
        $photos = ['list', 'of', 'photos'];
        $expected = ['nav' => $nav, 'photos' => $photos];

        Utility::setProperties(['galleryName' => $name], $this->testObj);

        $this->galleryMock->expects($this->once())
                          ->method('getGalleryNav')
                          ->with($this->identicalTo($name))
                          ->willReturn($nav);
        $this->galleryMock->expects($this->once())
                          ->method('getGalleryPhotos')
                          ->with($this->identicalTo($name))
                          ->willReturn($photos);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($expected, 'data', $this->testObj);
    }

    /**
     * @covers ::fetchData
     * @covers ::getNavData
     * @depends testFetchData
     */
    public function testFetchDataFailure()
    {
        $name = 'foobar';

        Utility::setProperties(['galleryName' => $name], $this->testObj);

        $this->galleryMock->expects($this->once())
                          ->method('getGalleryNav')
                          ->with($this->identicalTo($name))
                          ->willReturn(null);

        $this->setExpectedException('\DomainException');
        $this->testObj->fetchData();
    }


    /**
     * @covers ::constructView
     * @covers ::fillNav
     * @covers ::fillGallery
     */
    public function testConstructView()
    {
        $name = 'foobarphotos';
        $class = 'gallery-foobar';
        $data = [
            'nav' => [
                'next' => 'barbaz',
                'previous' => 'loremipsum',
            ],
            'photos' => [
                ['foobar1.jpg'],
                ['foobar2.jpg'],
                ['foobar3.jpg'],
            ],
        ];
        $heading = [
            'title' => 'FooBar Photos',
            'description' => 'gallery of foobar and foobar accessories'
        ];

        $properties = ['galleryName' => $name, 'galleryClass' => $class,
                       'data' => $data];
        Utility::setProperties($properties, $this->testObj);

        $this->testObj->expects($this->once())
                      ->method('getHeading')
                      ->willReturn($heading);
        $cbFunc = function($input) { return $input[0]; };
        $this->testObj->expects($this->exactly(count($data['photos'])))
                      ->method('wrapItem')
                      ->will($this->returnCallback($cbFunc));

        $this->standardConstructViewTest();
    }


    /**
     * Ensure construction still works if gallery is empty and orphaned
     * 
     * @covers ::constructView
     * @covers ::fillNav
     * @covers ::fillGallery
     * @depends testConstructView
     */
    public function testConstructViewEmptyGallery()
    {
        $name = 'foobarphotos';
        $class = 'gallery-foobar';
        $data = [
            'nav' => [
                'next' => '',
                'previous' => '',
            ],
            'photos' => [],
        ];
        $heading = [
            'title' => '',
            'description' => ''
        ];

        $properties = ['galleryName' => $name, 'galleryClass' => $class,
                       'data' => $data];
        Utility::setProperties($properties, $this->testObj);

        $this->testObj->method('getHeading')
                      ->willReturn($heading);
        $this->testObj->expects($this->never())
                      ->method('wrapItem');

        $this->standardConstructViewTest();
    }
}
