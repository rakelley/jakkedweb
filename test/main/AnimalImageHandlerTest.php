<?php
namespace test\main;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\AnimalImageHandler
 */
class AnimalImageHandlerTest extends \test\helpers\cases\Base
{
    protected $fileMock;
    protected $fileSystemMock;


    protected function setUp()
    {
        $fileInterface = '\rakelley\jhframe\interfaces\IFile';
        $fileSystemInterface =
            '\rakelley\jhframe\interfaces\services\IFileSystemAbstractor';
        $testedClass = '\main\AnimalImageHandler';

        $this->fileMock = $this->getMock($fileInterface);
        $this->fileSystemMock = $this->getMock($fileSystemInterface);

        $mockedMethods = [
            'Delete', //implemented by parent
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();

        $properties = [
            'fileSystem' => $this->fileSystemMock,
            'directory'  => '/foo/bar',
        ];
        Utility::setProperties($properties, $this->testObj);
    }


    /**
     * @covers ::Validate
     * @dataProvider fileProvider
     */
    public function testValidate($type, $size, $expected)
    {
        if ($type === 'valid') {
            $types = $this->readAttribute($this->testObj, 'validTypes');
            $type = $types[0];
        }
        $this->fileMock->method('getMedia')
                       ->willReturn($type);

        $size += $this->readAttribute($this->testObj, 'maxFileSize');
        $this->fileMock->method('getSize')
                       ->willReturn($size);

        $this->assertEquals(
            $expected,
            $this->testObj->Validate($this->fileMock)
        );
    }

    public function fileProvider()
    {
        return [
            ['valid', -1000, true], //valid
            ['valid', 1000, false], //invalid size
            ['fake/mime', -1000, false], //invalid type
        ];
    }


    /**
     * @covers ::Write
     * @depends testValidate
     */
    public function testWrite()
    {
        $key = 'any';
        $file = 'http://example.com/foo.jpg';
        $content = 'a string';
        $directory = $this->readAttribute($this->testObj, 'directory');
        $types = $this->readAttribute($this->testObj, 'validTypes');
        $type = $types[0];
        $size = $this->readAttribute($this->testObj, 'maxFileSize');

        $this->fileSystemMock->expects($this->once())
                             ->method('getRemoteFile')
                             ->With($this->identicalTo($file))
                             ->willReturn($content);
        $this->fileSystemMock->expects($this->once())
                             ->method('getFileWithPath')
                             ->with($this->logicalAnd(
                                    $this->stringContains($key),
                                    $this->stringContains($directory)
                                ))
                             ->willReturn($this->fileMock);

        $this->fileMock->method('getMedia')
                       ->willReturn($type);
        $this->fileMock->method('getSize')
                       ->willReturn($size);
        $this->fileMock->expects($this->once())
                       ->method('setContent')
                       ->with($this->identicalTo($content));

        $this->testObj->expects($this->once())
                      ->method('Delete')
                      ->with($this->identicalTo($key));

        $this->assertTrue($this->testObj->Write($key, $file));
    }


    /**
     * @covers ::Write
     * @depends testWrite
     */
    public function testWriteInvalid()
    {
        $key = 'any';
        $file = 'http://example.com/foo.jpg';
        $content = 'a string';
        $type = 'fake/mime';
        $size = $this->readAttribute($this->testObj, 'maxFileSize');

        $this->fileSystemMock->method('getRemoteFile')
                             ->willReturn($content);
        $this->fileSystemMock->method('getFileWithPath')
                             ->willReturn($this->fileMock);

        $this->fileMock->method('getMedia')
                       ->willReturn($type);
        $this->fileMock->method('getSize')
                       ->willReturn($size);
        $this->fileMock->expects($this->once())
                       ->method('Delete');

        $this->testObj->expects($this->once())
                      ->method('Delete')
                      ->with($this->identicalTo($key));

        $this->assertFalse($this->testObj->Write($key, $file));
    }


    /**
     * @covers ::Write
     * @depends testWrite
     */
    public function testWriteFailedFetch()
    {
        $key = 'any';
        $file = 'http://example.com/foo.jpg';

        $this->fileSystemMock->method('getRemoteFile')
                             ->willReturn(null);

        $this->testObj->expects($this->never())
                      ->method('Delete');

        $this->assertFalse($this->testObj->Write($key, $file));
    }
}
