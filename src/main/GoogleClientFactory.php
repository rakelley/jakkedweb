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
namespace main;

/**
 * Factory for \Google_Client
 */
class GoogleClientFactory implements \rakelley\jhframe\interfaces\IFactory
{
    use \rakelley\jhframe\traits\ConfigAware;

    /**
     * ApplicationName to set in client
     * @var string
     */
    protected $appName;
    /**
     * DeveloperKey to set in client
     * @var string
     */
    protected $apiKey;


    function __construct()
    {
        $this->appName = $this->getConfig()->Get('SECRETS', 'google_appname');
        $this->apiKey = $this->getConfig()->Get('SECRETS', 'google_apikey');
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\IFactory::getProduct()
     */
    public function getProduct()
    {
        if (!$this->appName || !$this->apiKey) {
            throw new \RuntimeException(
                'Cannot Create a Google API Client, Credentials Not Found',
                500
            );
        }

        $client = new \Google_Client();
        $client->setApplicationName($this->appName);
        $client->setDeveloperKey($this->apiKey);

        return $client;
    }
}
