<?php
namespace test\main\repositories;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\repositories\Youtube
 */
class YoutubeTest extends \test\helpers\cases\Base
{
    protected $ytMock;
    protected $playlistsMock;
    protected $channelsMock;
    protected $filterMock;


    protected function setUp()
    {
        $ytRealClass = 'Google_Service_YouTube';
        $filterInterface = '\rakelley\jhframe\interfaces\services\IFilter';

        $this->playlistsMock = $this->getMockBuilder('\stdClass')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['listPlaylistItems',
                                                  'getItems'])
                                    ->getMock();

        $this->channelsMock = $this->getMockBuilder('\stdClass')
                                   ->disableOriginalConstructor()
                                   ->setMethods(['listChannels', 'getItems'])
                                   ->getMock();

        $this->ytMock = $this->getMockBuilder('\stdClass')
                             ->setMockClassName($ytRealClass)
                             ->disableOriginalConstructor()
                             ->getMock();
        $this->ytMock->playlistItems = $this->playlistsMock;
        $this->ytMock->channels = $this->channelsMock;

        $this->filterMock = $this->getMock($filterInterface);
    }


    protected function setUpConstructor($username, $id)
    {
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $testedClass = '\main\repositories\Youtube';

        $configMock = $this->getMock($configInterface);
        $configMock->expects($this->at(0))
                   ->method('Get')
                   ->with($this->identicalTo('SECRETS'),
                          $this->identicalTo('google_ytusername'))
                   ->willReturn($username);
        if ($username) {
            $configMock->expects($this->at(1))
                       ->method('Get')
                       ->with($this->identicalTo('SECRETS'),
                              $this->identicalTo('google_ytuploadsid'))
                       ->willReturn($id);
        }

        $mockedMethods = [
            'getConfig',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getConfig')
                      ->willReturn($configMock);
        Utility::callConstructor($this->testObj, [$this->filterMock,
                                                  $this->ytMock]);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $username = 'foobar';
        $id = 'abcde';

        $this->setUpConstructor($username, $id);

        $this->assertAttributeEquals($this->filterMock, 'filter',
                                     $this->testObj);
        $this->assertAttributeEquals($this->ytMock, 'yt', $this->testObj);
        $this->assertAttributeEquals($username, 'username', $this->testObj);
        $this->assertAttributeEquals($id, 'uploadsId', $this->testObj);
    }


    /**
     * @covers ::__construct
     * @depends testConstruct
     */
    public function testConstructNoUsername()
    {
        $username = null;
        $id = 'abcde';

        $this->setExpectedException('\RuntimeException');
        $this->setUpConstructor($username, $id);
    }


    /**
     * @covers ::getRecentVideos
     * @depends testConstruct
     */
    public function testGetRecentVideos()
    {
        $username = 'foobar';
        $id = 'abcde';
        $this->setUpConstructor($username, $id);

        $videos = ['abcde', 'fghij', 'klmno'];
        $items = [
            ['snippet' => ['resourceId' => ['videoId' => $videos[0]]]],
            ['snippet' => ['resourceId' => ['videoId' => $videos[1]]]],
            ['snippet' => ['resourceId' => ['videoId' => $videos[2]]]],
        ];

        $this->playlistsMock->expects($this->once())
                            ->method('listPlaylistItems')
                            ->with($this->identicalTo('snippet'),
                                   $this->contains($id))
                            ->will($this->returnSelf());
        $this->playlistsMock->expects($this->once())
                            ->method('getItems')
                            ->willReturn($items);

        $this->filterMock->expects($this->exactly(count($videos)))
                         ->method('plainText')
                         ->will($this->returnArgument(0));

        $this->assertEquals($videos, $this->testObj->getRecentVideos());
    }

    /**
     * @covers ::getRecentVideos
     * @depends testGetRecentVideos
     */
    public function testGetRecentVideosBadId()
    {
        $username = 'foobar';
        $id = 'abcde';
        $this->setUpConstructor($username, $id);

        $videos = ['abcde', 'fghij', 'klmno'];
        $items = [
            ['snippet' => ['resourceId' => ['videoId' => $videos[0]]]],
            ['snippet' => ['resourceId' => ['videoId' => $videos[1]]]],
            ['snippet' => ['resourceId' => ['videoId' => $videos[2]]]],
        ];
        $expectedVideos = ['abcde', 'klmno'];

        $this->playlistsMock->method('listPlaylistItems')
                            ->will($this->returnSelf());
        $this->playlistsMock->method('getItems')
                            ->willReturn($items);

        $this->filterMock->expects($this->at(0))
                         ->method('plainText')
                         ->will($this->returnArgument(0));
        $this->filterMock->expects($this->at(1))
                         ->method('plainText')
                         ->willReturn(null);
        $this->filterMock->expects($this->at(2))
                         ->method('plainText')
                         ->will($this->returnArgument(0));

        $this->assertEquals($expectedVideos, $this->testObj->getRecentVideos());
    }

    /**
     * @covers ::getRecentVideos
     * @depends testGetRecentVideos
     */
    public function testGetRecentVideosEmptyPlaylist()
    {
        $username = 'foobar';
        $id = 'abcde';
        $this->setUpConstructor($username, $id);

        $items = [];

        $this->playlistsMock->method('listPlaylistItems')
                            ->will($this->returnSelf());
        $this->playlistsMock->method('getItems')
                            ->willReturn($items);

        $this->assertEquals(null, $this->testObj->getRecentVideos());
    }

    /**
     * @covers ::getRecentVideos
     * @covers ::getUploadsChannelForUser
     * @depends testGetRecentVideos
     */
    public function testGetRecentVideosNoPlaylist()
    {
        $username = 'foobar';
        $this->setUpConstructor($username, null);

        $id = 'fetched_id';
        $channels = [
            ['contentDetails' => ['relatedPlaylists' => ['uploads' => $id]]],
            ['contentDetails' => ['relatedPlaylists' => ['uploads' => 'other']]],
        ];

        $this->channelsMock->expects($this->once())
                           ->method('listChannels')
                           ->with($this->identicalTo('contentDetails'),
                                  $this->contains($username))
                           ->will($this->returnSelf());
        $this->channelsMock->expects($this->once())
                           ->method('getItems')
                           ->willReturn($channels);

        $videos = ['abcde', 'fghij', 'klmno'];
        $items = [
            ['snippet' => ['resourceId' => ['videoId' => $videos[0]]]],
            ['snippet' => ['resourceId' => ['videoId' => $videos[1]]]],
            ['snippet' => ['resourceId' => ['videoId' => $videos[2]]]],
        ];

        $this->playlistsMock->expects($this->once())
                            ->method('listPlaylistItems')
                            ->with($this->identicalTo('snippet'),
                                   $this->contains($id))
                            ->will($this->returnSelf());
        $this->playlistsMock->method('getItems')
                            ->willReturn($items);

        $this->filterMock->method('plainText')
                         ->will($this->returnArgument(0));

        $this->assertEquals($videos, $this->testObj->getRecentVideos());
    }
}
