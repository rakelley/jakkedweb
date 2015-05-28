<?php
namespace test\cms;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\FlatPageHandler
 */
class FlatPageHandlerTest extends \test\helpers\cases\Base
{
    protected $filesystemMock;


    protected function setUp()
    {
        $filesystemInterface =
            '\rakelley\jhframe\interfaces\services\IFileSystemAbstractor';
        $testedClass = '\cms\FlatPageHandler';

        $this->filesystemMock = $this->getMockBuilder($filesystemInterface)
                                     ->setMethods(['getContent', 'setContent'])
                                     ->getMockForAbstractClass();

        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods(null)
                              ->getMock();

        $properties = [
            'fileSystem' => $this->filesystemMock,
            'directory' => '/foo/bar/'
        ];
        Utility::setProperties($properties, $this->testObj);
    }


    /**
     * @covers ::Validate
     */
    public function testValidate()
    {
        $this->setExpectedException('\BadMethodCallException');
        $this->testObj->Validate('anything');
    }


    /**
     * @covers ::Delete
     */
    public function testDelete()
    {
        $name = 'foobar';

        $this->filesystemMock->expects($this->once())
                             ->method('Delete')
                             ->with($this->stringContains($name));

        $this->testObj->Delete($name);
    }


    /**
     * @covers ::Read
     * @covers ::<private>
     * @dataProvider readCaseProvider
     */
    public function testRead($exists, $content)
    {
        $name = 'foobar';

        $this->filesystemMock->expects($this->once())   
                             ->method('Exists')
                             ->with($this->stringContains($name))
                             ->willReturn($exists);
        $this->filesystemMock->method('getFileWithPath')
                             ->will($this->returnSelf());
        $this->filesystemMock->method('getContent')
                             ->willReturn($content);

        $this->assertEquals($content, $this->testObj->Read($name));
    }


    public function readCaseProvider()
    {
        return [
            [true, 'lorem ipsum'],
            [false, null],
        ];
    }


    /**
     * @covers ::Write
     * @covers ::<private>
     */
    public function testWrite()
    {
        $name = 'foobar';
        $content = 'lorem ipsum';

        $this->filesystemMock->expects($this->once())
                             ->method('getFileWithPath')
                             ->with($this->stringContains($name))
                             ->will($this->returnSelf());
        $this->filesystemMock->expects($this->once())
                             ->method('setContent')
                             ->with($this->identicalTo($content));

        $this->testObj->Write($name, $content);
    }
}
