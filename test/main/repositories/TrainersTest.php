<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\Trainers
 */
class TrainersTest extends \test\helpers\cases\Base
{
    protected $imageMock;
    protected $modelMock;


    protected function setUp()
    {
        $imageClass = '\main\TrainerImageHandler';
        $modelClass = '\main\models\Trainers';
        $testedClass = '\main\repositories\Trainers';

        $this->imageMock = $this->getMockBuilder($imageClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->imageMock, $this->modelMock);
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->imageMock, 'photoHandler',
                                     $this->testObj);
        $this->assertAttributeEquals($this->modelMock, 'trainerModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $trainers = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('alltrainers'))
                        ->willReturn($trainers);

        $this->assertEquals($trainers, $this->testObj->getAll());
    }


    /**
     * @covers ::getPhoto
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testGetPhoto()
    {
        $name = 'foobar';
        $path = 'foobar.jpg';

        $this->imageMock->expects($this->once())
                        ->method('Read')
                        ->with($this->stringContains($name))
                        ->willReturn($path);
        $this->imageMock->expects($this->once())
                        ->method('makeRelative')
                        ->with($this->identicalTo($path))
                        ->will($this->returnArgument(0));

        $this->assertEquals($path, $this->testObj->getPhoto($name));
    }

    /**
     * @covers ::getPhoto
     * @covers ::<protected>
     * @depends testGetPhoto
     */
    public function testGetPhotoNotFound()
    {
        $name = 'foobar';

        $this->imageMock->expects($this->once())
                        ->method('Read')
                        ->with($this->stringContains($name))
                        ->willReturn(null);

        $this->assertEquals(null, $this->testObj->getPhoto($name));
    }


    /**
     * @covers ::setPhoto
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testSetPhoto()
    {
        $name = 'foobar';
        $photo = 'foobar.jpg';

        $this->imageMock->expects($this->once())
                        ->method('Write')
                        ->with($this->identicalTo($name),
                               $this->identicalTo($photo));

        $this->testObj->setPhoto($name, $photo);
    }

    /**
     * @covers ::setPhoto
     * @covers ::<protected>
     * @depends testSetPhoto
     */
    public function testSetPhotoToNull()
    {
        $name = 'foobar';

        $this->imageMock->expects($this->once())
                        ->method('Delete')
                        ->with($this->identicalTo($name));

        $this->testObj->setPhoto($name, null);
    }


    /**
     * @covers ::validatePhoto
     * @depends testConstruct
     */
    public function testValidatePhoto()
    {
        $photo = ['tmp_name' => 'foobar', 'size' => 314159];

        $this->imageMock->expects($this->at(0))
                        ->method('Validate')
                        ->with($this->identicalTo($photo))
                        ->willReturn(true);
        $this->imageMock->expects($this->at(1))
                        ->method('Validate')
                        ->with($this->identicalTo($photo))
                        ->willReturn(false);

        $this->assertTrue($this->testObj->validatePhoto($photo));
        $this->assertFalse($this->testObj->validatePhoto($photo));
    }


    /**
     * @covers ::getTrainer
     * @depends testGetPhoto
     */
    public function testGetTrainer()
    {
        $name = 'foobar';
        $properties = ['lorem' => 'ipsum'];
        $photo = 'foobar.jpg';
        $expected = array_merge($properties, ['photo' => $photo]);

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['name' => $name]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('trainer'))
                        ->willReturn($properties);

        $this->imageMock->method('Read')
                        ->with($this->stringContains($name))
                        ->willReturn($photo);
        $this->imageMock->method('makeRelative')
                        ->with($this->identicalTo($photo))
                        ->will($this->returnArgument(0));

        $this->assertEquals($expected, $this->testObj->getTrainer($name));
    }

    /**
     * @covers ::getTrainer
     * @depends testGetTrainer
     */
    public function testGetTrainerNotFound()
    {
        $name = 'foobar';

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['name' => $name]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('trainer'))
                        ->willReturn(null);

        $this->imageMock->expects($this->never())
                        ->method('Read');
        $this->imageMock->expects($this->never())
                        ->method('makeRelative');

        $this->assertEquals(null, $this->testObj->getTrainer($name));
    }


    /**
     * @covers ::setTrainer
     * @depends testConstruct
     */
    public function testSetTrainer()
    {
        $input = ['name' => 'foobar', 'lorem' => 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['name' => $input['name']]));
        $this->modelMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('trainer'),
                               $this->identicalTo($input));

        $this->testObj->setTrainer($input);
    }


    /**
     * @covers ::addTrainer
     * @depends testConstruct
     */
    public function testAddTrainer()
    {
        $input = ['name' => 'foobar', 'lorem' => 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($input));

        $this->testObj->addTrainer($input);
    }


    /**
     * @covers ::deleteTrainer
     * @depends testSetPhoto
     */
    public function testDeleteTrainer()
    {
        $name = 'foobar';

        $this->imageMock->expects($this->once())
                        ->method('Delete')
                        ->with($this->identicalTo($name));

        $this->modelMock->expects($this->once())
                        ->method('Delete')
                        ->with($this->identicalTo($name));

        $this->testObj->deleteTrainer($name);
    }


    /**
     * @covers ::getVisible
     * @depends testGetPhoto
     */
    public function testGetVisible()
    {
        $trainers = [['name' => 'foo'], ['name' => 'bar'], ['name' => 'baz']];
        $photo = 'generic.jpg';
        $expected = [
            ['name' => 'foo', 'photo' => $photo],
            ['name' => 'bar', 'photo' => $photo],
            ['name' => 'baz', 'photo' => $photo],
        ];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('allvisible'))
                        ->willReturn($trainers);

        $this->imageMock->expects($this->exactly(count($trainers)))
                        ->method('Read')
                        ->willReturn($photo);
        $this->imageMock->method('makeRelative')
                        ->will($this->returnArgument(0));

        $this->assertEquals($expected, $this->testObj->getVisible());
    }
}
