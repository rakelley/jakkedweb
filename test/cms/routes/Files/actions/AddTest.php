<?php
namespace test\cms\routes\Files\actions;

use \cms\routes\Files\actions\Add;
use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Files\actions\Add
 */
class AddTest extends \test\helpers\cases\Base
{
    protected $handlerMock;
    protected $basePath = 'http://cms.example.com';
    protected $publicDir = '/foo/bar/pub/';

    protected function setUp()
    {
        $viewClass = '\cms\routes\Files\views\AddForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $testedClass = '\cms\routes\Files\actions\Add';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $configMock = $this->getMock($configInterface);
        $configMock->expects($this->at(0))
                   ->method('Get')
                   ->with($this->identicalTo('APP'),
                          $this->identicalTo('base_path'))
                   ->willReturn($this->basePath);
        $configMock->expects($this->at(1))
                   ->method('Get')
                   ->with($this->identicalTo('ENV'),
                          $this->identicalTo('public_dir'))
                   ->willReturn($this->publicDir);

        $mockedMethods = [
            'getConfig',//trait implemented
            'getLocator',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getConfig')
                      ->willReturn($configMock);
        Utility::callConstructor($this->testObj, [$viewMock, $validatorMock]);
    }

    protected function setUpGenericHandler()
    {
        $interface = '\rakelley\jhframe\interfaces\IFileHandler';
        $this->handlerMock = $this->getMock($interface);
        Utility::setProperties(['handler' => $this->handlerMock],
                               $this->testObj);
    }

    protected function setUpMatchingHandler($property)
    {
        $class = $this->readAttribute($this->testObj, $property);
        $this->handlerMock = $this->getMockBuilder($class)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $locatorInterface = '\rakelley\jhframe\interfaces\services\IServiceLocator';
        $locatorMock = $this->getMock($locatorInterface);
        $this->testObj->method('getLocator')
                      ->willReturn($locatorMock);

        $locatorMock->expects($this->once())
                    ->method('Make')
                    ->with($this->identicalTo($class))
                    ->willReturn($this->handlerMock);
    }


    public function validationCaseProvider()
    {
        return [
            [//pdf
                [
                    'filetype' => Add::TYPE_PDF,
                    'file' => ['name' => 'foobar.pdf'],
                    'overwrite' => false,
                ],
                'handlerPdf',
                true
            ],
            [//article
                [
                    'filetype' => Add::TYPE_ARTICLE,
                    'file' => ['name' => 'foobar.jpg'],
                    'overwrite' => false,
                ],
                'handlerArticle',
                true
            ],
            [//gallery
                [
                    'filetype' => Add::TYPE_GALLERY,
                    'file' => ['name' => 'foobar.jpg'],
                    'gallery' => 'lorem',
                    'overwrite' => false,
                ],
                'handlerGallery',
                true
            ],
            [//gallery with no gallery picked
                [
                    'filetype' => Add::TYPE_GALLERY,
                    'file' => ['name' => 'foobar.jpg'],
                    'overwrite' => false,
                ],
                'handlerGallery',
                false
            ],
            [//unknown type
                [
                    'filetype' => 'unknown file type',
                    'file' => ['name' => 'foobar.jpg'],
                    'overwrite' => false,
                ],
                null,
                false
            ],
        ];
    }


    public function overwriteCaseProvider()
    {
        return [
            [//passes
                [
                    'filetype' => Add::TYPE_PDF,
                    'file' => ['name' => 'foobar.pdf'],
                    'overwrite' => true,
                ],
                'handlerPdf',
                true
            ],
            [//fails
                [
                    'filetype' => Add::TYPE_PDF,
                    'file' => ['name' => 'foobar.pdf'],
                ],
                'handlerPdf',
                false
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->basePath, 'basePath',
                                     $this->testObj);
        $this->assertAttributeEquals($this->publicDir, 'publicDir',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     */
    public function testProceed()
    {
        $this->setUpGenericHandler();
        $key = 'foobar';
        $input = ['file' => 'lorem ipsum'];
        Utility::setProperties(['key' => $key, 'input' => $input],
                               $this->testObj);

        $this->handlerMock->expects($this->once())
                          ->method('Write')
                          ->with($this->identicalTo($key),
                                 $this->identicalTo($input['file']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::getResult
     * @depends testConstruct
     */
    public function testGetResult()
    {
        $this->setUpGenericHandler();
        $key = 'foobar';
        $path = $this->publicDir . 'lorem.jpg';
        Utility::setProperties(['key' => $key], $this->testObj);

        $this->handlerMock->expects($this->once())
                          ->method('Read')
                          ->with($this->identicalTo($key))
                          ->willReturn($path);

        $result = $this->testObj->getResult();
        $this->assertTrue(strlen($result) > 1);
    }


    /**
     * @covers ::<protected>
     * @dataProvider validationCaseProvider
     */
    public function testValidateInput($input, $handlerProp, $passes)
    {
        Utility::setProperties(['input' => $input], $this->testObj);

        if ($passes) {
            $this->setUpMatchingHandler($handlerProp);
            $this->handlerMock->expects($this->once())
                              ->method('Read')
                              ->with($this->isType('string'))
                              ->willReturn(false);
            $this->handlerMock->expects($this->once())
                              ->method('Validate')
                              ->with($this->identicalTo($input['file']));
        } else {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }

        Utility::callMethod($this->testObj, 'validateInput');
    }


    /**
     * @covers ::validateInput
     * @depends testValidateInput
     * @dataProvider overwriteCaseProvider
     */
    public function testValidateInputWithOverwrites($input, $handlerProp,
                                                    $passes)
    {
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->setUpMatchingHandler($handlerProp);
        $this->handlerMock->expects($this->once())
                          ->method('Read')
                          ->with($this->isType('string'))
                          ->willReturn(true);
        if (!$passes) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }

        Utility::callMethod($this->testObj, 'validateInput');
    }
}
