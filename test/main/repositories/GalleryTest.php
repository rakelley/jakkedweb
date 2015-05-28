<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\Gallery
 */
class GalleryTest extends \test\helpers\cases\Base
{
    protected $pageMock;
    protected $navMock;
    protected $imageMock;


    protected function setUp()
    {
        $pageClass = '\main\repositories\PageData';
        $navClass = '\main\repositories\Navigation';
        $imageClass = '\main\GalleryImageHandler';
        $testedClass = '\main\repositories\Gallery';

        $this->pageMock = $this->getMockBuilder($pageClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->navMock = $this->getMockBuilder($navClass)
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->imageMock = $this->getMockBuilder($imageClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->pageMock, $this->navMock,
                                          $this->imageMock);
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->pageMock, 'pageData',
                                     $this->testObj);
        $this->assertAttributeEquals($this->navMock, 'navEntries',
                                     $this->testObj);
        $this->assertAttributeEquals($this->imageMock, 'imageHandler',
                                     $this->testObj);
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $pages = ['gallery/foo', 'gallery/bar', 'gallery/', 'gallery/baz'];
        $expected = ['foo', 'bar', 'baz'];

        $this->pageMock->expects($this->once())
                       ->method('getGroup')
                       ->with($this->identicalTo('gallery'))
                       ->willReturn($pages);

        $this->assertEquals($expected, $this->testObj->getAll());
    }

    /**
     * @covers ::getAll
     * @depends testGetAll
     */
    public function testGetAllEmpty()
    {
        $this->pageMock->expects($this->once())
                       ->method('getGroup')
                       ->with($this->identicalTo('gallery'))
                       ->willReturn(null);

        $this->assertEquals(null, $this->testObj->getAll());
    }


    /**
     * @covers ::getGallery
     * @depends testConstruct
     * @dataProvider galleryProvider
     */
    public function testGetGallery($name, $properties)
    {
        $expectedRoute = 'gallery/' . $name;

        $this->pageMock->expects($this->once())
                       ->method('getPage')
                       ->with($this->identicalTo($expectedRoute))
                       ->willReturn($properties);

        $this->assertEquals($properties, $this->testObj->getGallery($name));
    }

    public function galleryProvider()
    {
        return [
            ['foo', ['lorem', 'ipsum']],
            ['bar', null],
        ];
    }


    /**
     * @covers ::galleryExists
     * @depends testGetGallery
     * @dataProvider galleryProvider
     */
    public function testGalleryExists($name, $properties)
    {
        $expected = (bool) $properties;

        $this->pageMock->method('getPage')
                       ->with($this->stringContains($name))
                       ->willReturn($properties);

        $this->assertEquals($expected, $this->testObj->galleryExists($name));
    }


    /**
     * @covers ::addGallery
     * @depends testConstruct
     */
    public function testAddGallery()
    {
        $input = [
            'name' => 'foobar',
            'indeximage' => 'bazbat.jpg',
        ];

        $this->imageMock->expects($this->once())
                        ->method('addGallery')
                        ->with($this->identicalTo($input['name']));
        $this->imageMock->expects($this->once())
                        ->method('Write')
                        ->with($this->stringContains($input['name']),
                               $this->identicalTo($input['indeximage']));

        $this->pageMock->expects($this->once())
                       ->method('addPage')
                       ->with($this->logicalAnd(
                            $this->arrayHasKey('name'),
                            $this->arrayHasKey('route'),
                            $this->arrayHasKey('pagegroup'),
                            $this->arrayHasKey('priority')
                        ));

        $this->navMock->expects($this->once())
                      ->method('addEntry')
                      ->with($this->logicalAnd(
                            $this->arrayHasKey('route'),
                            $this->arrayHasKey('title'),
                            $this->arrayHasKey('parent'),
                            $this->arrayHasKey('description')
                        ));

        $this->testObj->addGallery($input);
    }


    /**
     * @covers ::deleteGallery
     * @depends testConstruct
     */
    public function testDeleteGallery()
    {
        $name = 'foobar';

        $this->navMock->expects($this->once())
                      ->method('deleteEntry')
                      ->with($this->stringContains($name));

        $this->pageMock->expects($this->once())
                       ->method('deletePage')
                       ->with($this->stringContains($name));

        $this->imageMock->expects($this->once())
                        ->method('Delete')
                        ->with($this->stringContains($name));
        $this->imageMock->expects($this->once())
                        ->method('deleteGallery')
                        ->with($this->identicalTo($name));

        $this->testObj->deleteGallery($name);
    }


    /**
     * @covers ::getGalleryNav
     * @depends testGetAll
     * @dataProvider navCaseProvider
     */
    public function testGetGalleryNav($name, $galleries, $expected)
    {
        $this->pageMock->expects($this->once())
                       ->method('getGroup')
                       ->with($this->identicalTo('gallery'))
                       ->willReturn($galleries);

        $this->assertEquals(
            $expected,
            $this->testObj->getGalleryNav($name)
        );
    }

    public function navCaseProvider()
    {
        return [
            [//in list
                'bar',
                ['gallery/foo', 'gallery/bar', 'gallery/baz'],
                ['previous' => 'foo', 'next' => 'baz'],
            ],
            [//first in list
                'foo',
                ['gallery/foo', 'gallery/bar', 'gallery/baz'],
                ['previous' => null, 'next' => 'bar'],
            ],
            [//last in list
                'baz',
                ['gallery/foo', 'gallery/bar', 'gallery/baz'],
                ['previous' => 'bar', 'next' => null],
            ],
            [//not in list
                'lorem',
                ['gallery/foo', 'gallery/bar', 'gallery/baz'],
                null,
            ],
        ];
    }


    /**
     * @covers ::getGalleryPhotos
     * @depends testConstruct
     */
    public function testGetGalleryPhotos()
    {
        $name = 'foobar';
        $photos = ['foobar1.jpg', 'foobar2.jpg'];

        $this->imageMock->expects($this->once())
                        ->method('getGallery')
                        ->with($this->identicalTo($name))
                        ->willReturn($photos);

        $this->assertEquals($photos, $this->testObj->getGalleryPhotos($name));
    }


    /**
     * @covers ::getIndex
     * @depends testGetAll
     */
    public function testGetIndex()
    {
        $galleries = ['gallery/foo', 'gallery/bar', 'gallery/baz'];

        $this->pageMock->expects($this->once())
                       ->method('getGroup')
                       ->with($this->identicalTo('gallery'))
                       ->willReturn($galleries);

        $this->imageMock->expects($this->exactly(count($galleries)))
                        ->method('makeRelative')
                        ->will($this->returnArgument(0));
        $this->imageMock->expects($this->exactly(count($galleries)))
                        ->method('Read')
                        ->with($this->stringContains('galleryindex/'))
                        ->willReturn('loremipsum.jpg');

        $result = $this->testObj->getIndex();
        $this->assertEquals(count($galleries), count($result));
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('title', $result[0]);
        $this->assertEquals('loremipsum.jpg', $result[0]['image']);
    }

    /**
     * @covers ::getIndex
     * @depends testGetIndex
     * @depends testGetAllEmpty
     */
    public function testGetIndexEmpty()
    {
        $this->pageMock->expects($this->once())
                       ->method('getGroup')
                       ->with($this->identicalTo('gallery'))
                       ->willReturn(null);

        $this->assertEquals(null, $this->testObj->getIndex());
    }


    /**
     * @covers ::validateImage
     * @depends testConstruct
     */
    public function testValidateImage()
    {
        $argA = ['anything'];
        $argB = 'anything';
        $valid = true;
        $invalid = false;

        $this->imageMock->expects($this->at(0))
                        ->method('Validate')
                        ->with($this->identicalTo($argA))
                        ->willReturn($valid);
        $this->imageMock->expects($this->at(1))
                        ->method('Validate')
                        ->with($this->identicalTo($argB))
                        ->willReturn($invalid);

        $this->assertEquals($valid, $this->testObj->validateImage($argA));
        $this->assertEquals($invalid, $this->testObj->validateImage($argB));
    }
}
