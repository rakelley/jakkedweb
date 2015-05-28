<?php
namespace test\main;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\GalleryImageHandler
 */
class GalleryImageHandlerTest extends \test\helpers\cases\Base
{
    protected $fileSystemMock;


    protected function setUp()
    {
        $fileSystemInterface =
            '\rakelley\jhframe\interfaces\services\IFileSystemAbstractor';
        $testedClass = '\main\GalleryImageHandler';

        $this->fileSystemMock = $this->getMock($fileSystemInterface);

        $mockedMethods = [
            'Read', //defined by parent
            'makeRelative', //defined by parent
            'getExtension', //defined by trait
            'createThumbnail', //defined by trait
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $properties = [
            'fileSystem' => $this->fileSystemMock,
            'directory' => '/foo/bar/',
        ];
        Utility::setProperties($properties, $this->testObj);
    }



    /**
     * @covers ::Delete
     * @covers ::<protected>
     */
    public function testDelete()
    {
        $key = 'example/foobar';
        $file = '/foo/bar/example/foobar.jpeg';
        $expectedThumb = '/foo/bar/example/thumbs/foobar.jpeg';

        $this->testObj->expects($this->once())
                      ->method('Read')
                      ->with($this->identicalTo($key))
                      ->willReturn($file);

        $this->fileSystemMock->expects($this->at(0))
                             ->method('Delete')
                             ->with($this->identicalTo($file));
        $this->fileSystemMock->expects($this->at(1))
                             ->method('Delete')
                             ->with($this->identicalTo($expectedThumb));

        $this->testObj->Delete($key);
    }

    /**
     * @covers ::Delete
     * @covers ::<protected>
     */
    public function testDeleteIndexImage()
    {
        $key = 'galleryindex/foobar';
        $file = '/foo/bar/galleryindex/foobar.jpeg';

        $this->testObj->expects($this->once())
                      ->method('Read')
                      ->with($this->identicalTo($key))
                      ->willReturn($file);

        $this->fileSystemMock->expects($this->once())
                             ->method('Delete')
                             ->with($this->identicalTo($file));

        $this->testObj->Delete($key);
    }

    /**
     * @covers ::Delete
     * @covers ::<protected>
     */
    public function testDeleteNotFound()
    {
        $key = 'example/foobar';

        $this->testObj->expects($this->once())
                      ->method('Read')
                      ->with($this->identicalTo($key))
                      ->willReturn(null);

        $this->fileSystemMock->expects($this->never())
                             ->method('Delete');

        $this->testObj->Delete($key);
    }


    /**
     * @covers ::Write
     * @covers ::<protected>
     * @depends testDelete
     */
    public function testWrite()
    {
        $key = 'example/foobar';
        $directory = $this->readAttribute($this->testObj, 'directory');
        $ext = '.jpeg';
        $file = ['tmp_name' => 'a string'];
        $expectedPath = $directory . $key . $ext;

        $this->testObj->expects($this->once())
                      ->method('getExtension')
                      ->with($this->identicalTo($file))
                      ->willReturn($ext);
        $this->testObj->expects($this->once())
                      ->method('createThumbnail')
                      ->with($this->identicalTo($expectedPath));


        $this->fileSystemMock->expects($this->once())
                             ->method('writeUploaded')
                             ->with($this->identicalTo($file['tmp_name']),
                                    $this->identicalTo($expectedPath));

        $this->testObj->Write($key, $file);
    }

    /**
     * @covers ::Write
     * @covers ::<protected>
     * @depends testWrite
     */
    public function testWriteIndexImage()
    {
        $key = 'galleryindex/foobar';
        $directory = $this->readAttribute($this->testObj, 'directory');
        $ext = '.jpeg';
        $file = ['tmp_name' => 'a string'];
        $expectedPath = $directory . $key . $ext;

        $this->testObj->method('getExtension')
                      ->with($this->identicalTo($file))
                      ->willReturn($ext);
        $this->testObj->expects($this->never())
                      ->method('createThumbnail');


        $this->fileSystemMock->expects($this->once())
                             ->method('writeUploaded')
                             ->with($this->identicalTo($file['tmp_name']),
                                    $this->identicalTo($expectedPath));

        $this->testObj->Write($key, $file);
    }


    /**
     * @covers ::getGallery
     */
    public function testGetGallery()
    {
        $directory = $this->readAttribute($this->testObj, 'directory');
        $gallery = 'foobar';
        $expectedPattern = $directory . $gallery . '/*.*';
        $files = ['foobar/baz.jpeg', 'foobar/bat.jpeg', 'foobar/lorem.jpeg'];

        $this->fileSystemMock->expects($this->once())
                             ->method('Glob')
                             ->with($this->identicalTo($expectedPattern))
                             ->willReturn($files);

        $this->testObj->method('makeRelative')
                      ->will($this->returnArgument(0));

        $results = $this->testObj->getGallery($gallery);
        $this->assertEquals(count($results), count($files));
        array_walk(
            $results,
            function($result) {
                $this->assertArrayHasKey('original', $result);
                $this->assertArrayHasKey('thumb', $result);
            }
        );
    }

    /**
     * @covers ::getGallery
     * @depends testGetGallery
     */
    public function testGetGalleryEmpty()
    {
        $directory = $this->readAttribute($this->testObj, 'directory');
        $gallery = 'foobar';
        $expectedPattern = $directory . $gallery . '/*.*';

        $this->fileSystemMock->expects($this->once())
                             ->method('Glob')
                             ->with($this->identicalTo($expectedPattern))
                             ->willReturn(null);

        $this->assertEquals(null, $this->testObj->getGallery($gallery));
    }


    /**
     * @covers ::addGallery
     */
    public function testAddGallery()
    {
        $directory = $this->readAttribute($this->testObj, 'directory');
        $gallery = 'foobar';
        $expectedDir = $directory . $gallery;
        $expectedThumbDir = $expectedDir . '/thumbs';

        $this->fileSystemMock->expects($this->at(0))
                             ->method('createDirectory')
                             ->with($this->identicalTo($expectedDir));
        $this->fileSystemMock->expects($this->at(1))
                             ->method('createDirectory')
                             ->with($this->identicalTo($expectedThumbDir));

        $this->testObj->addGallery($gallery);
    }


    /**
     * @covers ::deleteGallery
     */
    public function testDeleteGallery()
    {
        $directory = $this->readAttribute($this->testObj, 'directory');
        $gallery = 'foobar';
        $expectedDir = $directory . $gallery;

        $this->fileSystemMock->expects($this->once())
                             ->method('Delete')
                             ->with($this->identicalTo($expectedDir),
                                    $this->identicalTo(true));

        $this->testObj->deleteGallery($gallery);
    }
}
