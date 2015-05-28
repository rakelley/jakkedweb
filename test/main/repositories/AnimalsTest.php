<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\Animals
 */
class AnimalsTest extends \test\helpers\cases\Base
{
    protected $petfinderMock;
    protected $imageMock;
    protected $modelMock;


    protected function setUp()
    {
        $petfinderClass = '\main\repositories\PetFinderAPI';
        $imageClass = '\main\AnimalImageHandler';
        $modelClass = '\main\models\Animals';
        $testedClass = '\main\repositories\Animals';

        $this->petfinderMock = $this->getMockBuilder($petfinderClass)
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->imageMock = $this->getMockBuilder($imageClass)
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $mockedMethods = [
            'logException', //trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->petfinderMock,
                                                    $this->imageMock,
                                                    $this->modelMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->petfinderMock, 'petfinder',
                                     $this->testObj);
        $this->assertAttributeEquals($this->imageMock, 'imageHandler',
                                     $this->testObj);
        $this->assertAttributeEquals($this->modelMock, 'animalModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getAnimals
     * @depends testConstruct
     */
    public function testGetAnimals()
    {
        $animals = [
            ['number' => 001],
            ['number' => 004],
            ['number' => 007],
        ];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('animals'))
                        ->willReturn($animals);

        $this->imageMock->expects($this->exactly(count($animals)))
                        ->method('Read')
                        ->with($this->isType('int'))
                        ->willReturn('image.jpg');
        $this->imageMock->expects($this->exactly(count($animals)))
                        ->method('makeRelative')
                        ->will($this->returnArgument(0));

        $result = $this->testObj->getAnimals();
        $this->assertEquals(count($animals), count($result));
        $this->assertEquals('image.jpg', $result[0]['photo']);
    }

    /**
     * @covers ::getAnimals
     * @depends testGetAnimals
     */
    public function testGetAnimalsNoneFound()
    {
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('animals'))
                        ->willReturn(null);

        $this->assertEquals(null, $this->testObj->getAnimals());
    }


    /**
     * @covers ::Update
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testUpdate()
    {
        $newList = [
            ['number' => 004, 'name' => 'ipsum', 'img' => '004.jpg'],
            ['number' => 007, 'name' => 'dolor', 'img' => '007.jpg'],
            ['number' => 010, 'name' => 'sit', 'img' => '010.jpg'],
        ];
        $oldList = [
            ['number' => 001, 'name' => 'lorem'],
            ['number' => 004, 'name' => 'ipsum'],
        ];
        $expectedAdd = [[007, 'dolor'], [010, 'sit']];
        $expectedDelete = [001];

        $this->petfinderMock->expects($this->once())
                            ->method('getLatest')
                            ->willReturn($newList);

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('animals'))
                        ->willReturn($oldList);
        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($expectedAdd));
        $this->modelMock->expects($this->once())
                        ->method('Delete')
                        ->with($this->identicalTo($expectedDelete));

        $this->imageMock->expects($this->at(0))
                        ->method('Write')
                        ->with($this->identicalTo($newList[1]['number']),
                               $this->identicalTo($newList[1]['img']))
                        ->willReturn(true);
        $this->imageMock->expects($this->at(1))
                        ->method('Write')
                        ->with($this->identicalTo($newList[2]['number']),
                               $this->identicalTo($newList[2]['img']))
                        ->willReturn(true);
        $this->imageMock->expects($this->once())
                        ->method('Delete')
                        ->with($this->identicalTo($expectedDelete[0]));

        $this->assertTrue($this->testObj->Update());
    }

    /**
     * @covers ::Update
     * @covers ::<protected>
     * @depends testUpdate
     */
    public function testUpdateNoExisting()
    {
        $newList = [
            ['number' => 004, 'name' => 'ipsum', 'img' => '004.jpg'],
            ['number' => 007, 'name' => 'dolor', 'img' => '007.jpg'],
            ['number' => 010, 'name' => 'sit', 'img' => '010.jpg'],
        ];
        $oldList = null;
        $expectedAdd = [[004, 'ipsum'], [007, 'dolor'], [010, 'sit']];

        $this->petfinderMock->expects($this->once())
                            ->method('getLatest')
                            ->willReturn($newList);

        $this->modelMock->method('__get')
                        ->with($this->identicalTo('animals'))
                        ->willReturn($oldList);
        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($expectedAdd));
        $this->modelMock->expects($this->never())
                        ->method('Delete');

        $this->imageMock->expects($this->exactly(count($newList)))
                        ->method('Write')
                        ->willReturn(true);
        $this->imageMock->expects($this->never())
                        ->method('Delete');

        $this->assertTrue($this->testObj->Update());
    }

    /**
     * @covers ::Update
     * @covers ::<protected>
     * @depends testUpdate
     */
    public function testUpdateNoChanges()
    {
        $animals = [
            ['number' => 001, 'name' => 'lorem'],
            ['number' => 004, 'name' => 'ipsum'],
            ['number' => 007, 'name' => 'dolor'],
        ];

        $this->petfinderMock->expects($this->once())
                            ->method('getLatest')
                            ->willReturn($animals);

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('animals'))
                        ->willReturn($animals);
        $this->modelMock->expects($this->never())
                        ->method('Add');
        $this->modelMock->expects($this->never())
                        ->method('Delete');

        $this->imageMock->expects($this->never())
                        ->method('Write');
        $this->imageMock->expects($this->never())
                        ->method('Delete');

        $this->assertTrue($this->testObj->Update());
    }


    /**
     * @covers ::Update
     * @covers ::<protected>
     * @depends testUpdate
     */
    public function testUpdateWithImageFailures()
    {
        $newList = [
            ['number' => 004, 'name' => 'ipsum', 'img' => '004.jpg'],
            ['number' => 007, 'name' => 'dolor', 'img' => '007.jpg'],
            ['number' => 010, 'name' => 'sit', 'img' => '010.jpg'],
        ];
        $oldList = [
            ['number' => 004, 'name' => 'ipsum'],
        ];
        $expectedAdd = [[007, 'dolor']];

        $this->petfinderMock->method('getLatest')
                            ->willReturn($newList);

        $this->modelMock->method('__get')
                        ->with($this->identicalTo('animals'))
                        ->willReturn($oldList);
        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($expectedAdd));

        $this->imageMock->expects($this->at(0))
                        ->method('Write')
                        ->with($this->identicalTo($newList[1]['number']),
                               $this->identicalTo($newList[1]['img']))
                        ->willReturn(true);
        $this->imageMock->expects($this->at(1))
                        ->method('Write')
                        ->with($this->identicalTo($newList[2]['number']),
                               $this->identicalTo($newList[2]['img']))
                        ->willReturn(false);

        $this->assertTrue($this->testObj->Update());
    }

    /**
     * @covers ::Update
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testUpdateWithAPIException()
    {
        $runtimeE = new \RuntimeException('test exception');

        $this->petfinderMock->expects($this->once())
                            ->method('getLatest')
                            ->will($this->throwException($runtimeE));

        $this->testObj->expects($this->once())
                      ->method('logException')
                      ->with($this->identicalTo($runtimeE),
                             $this->isType('string'));

        $this->assertFalse($this->testObj->Update());
    }

    /**
     * @covers ::Update
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testUpdateWithGeneralException()
    {
        $regularE = new \Exception('test exception');

        $this->petfinderMock->expects($this->once())
                            ->method('getLatest')
                            ->will($this->throwException($regularE));

        $this->testObj->expects($this->once())
                      ->method('logException')
                      ->with($this->identicalTo($regularE),
                             $this->isType('string'));

        $this->assertFalse($this->testObj->Update());
    }
}
