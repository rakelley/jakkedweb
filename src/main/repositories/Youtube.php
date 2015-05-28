<?php
/**
 * @package jakkedweb
 * @subpackage main
 * 
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */
namespace main\repositories;

/**
 * Repository for interacting with 3rd party Youtube service
 */
class Youtube extends \rakelley\jhframe\classes\Repository
{
    use \rakelley\jhframe\traits\ConfigAware;

    /**
     * Filter library instance
     * @var object
     */
    protected $filter;
    /**
     * ChannelID for user upload playlist
     * @var string
     */
    protected $uploadsId;
    /**
     * Youtube username
     * @var string
     */
    protected $username;
    /**
     * \Google_Service_Youtube instance
     * @var object
     */
    protected $yt;


    function __construct(
        \rakelley\jhframe\interfaces\services\IFilter $filter,
        \Google_Service_YouTube $yt
    ) {
        $this->filter = $filter;
        $this->yt = $yt;

        $this->username = $this->getConfig()->Get('SECRETS',
                                                  'google_ytusername');
        if (!$this->username) {
            throw new \RuntimeException(
                'No google_ytusername Value Set During Application Setup',
                500
            );
        }

        $this->uploadsId = $this->getConfig()->Get('SECRETS',
                                                   'google_ytuploadsid');
    }


    /**
     * Get video ids of count most recently uploaded videos
     * 
     * @param  integer    $count How many ids to return
     * @return array|null
     */
    public function getRecentVideos($count=3)
    {
        $playlist = ($this->uploadsId) ?:
                    $this->getUploadsChannelForUser($this->username);
        $params = [
            'playlistId' => $playlist,
            'maxResults' => $count,
        ];
        $items = $this->yt->playlistItems->listPlaylistItems('snippet', $params)
                                         ->getItems();
        if (!$items) {
            return null;
        }

        $ids = array_map(
            function ($item) {
                $id = $item['snippet']['resourceId']['videoId'];
                return $this->filter->plainText($id);
            },
            $items
        );
        $ids = array_values(array_filter($ids));

        return ($ids) ?: null;
    }


    /**
     * Internal fallback method for deriving upload channel id for username
     * 
     * @return string
     */
    protected function getUploadsChannelForUser($username)
    {
        $params = [
            'forUsername' => $username,
        ];
        $channels = $this->yt->channels->listChannels('contentDetails', $params)
                                       ->getItems();

        $id = $channels[0]['contentDetails']['relatedPlaylists']['uploads'];

        return $this->filter->plainText($id);
    }
}
