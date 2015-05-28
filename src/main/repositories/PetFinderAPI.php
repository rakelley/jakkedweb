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
 * Repository for interacting with Petfinder.com API
 * @link https://www.petfinder.com/developers/api-docs
 */
class PetFinderAPI extends \rakelley\jhframe\classes\Repository
{
    use \rakelley\jhframe\traits\ConfigAware;

    /**
     * CurlAbstractor instance
     * @var object
     */
    protected $curl;
    /**
     * IFilter instance
     * @var object
     */
    protected $filter;
    /**
     * Private API key
     * @var string
     */
    protected $key;


    function __construct(
        \rakelley\jhframe\classes\CurlAbstractor $curl,
        \rakelley\jhframe\interfaces\services\IFilter $filter
    ) {
        $this->curl = $curl;
        $this->filter = $filter;

        $this->key = $this->getConfig()->Get('SECRETS', 'PFAPI_KEY');
    }


    /**
     * Gets current list of animals
     * 
     * @return array
     * @throws \RuntimeException if remote request fails or result is malformed
     */
    public function getLatest()
    {
        $uri = 'https://api.petfinder.com/shelter.getPets?id=IL36&format=json' .
               '&key=' . $this->key;
        $result = $this->curl->newRequest($uri)
                             ->setReturn()
                             ->Execute();
        $this->curl->Close();

        $animals = $this->filterResult($result);

        if (!$animals) {
            throw new \RuntimeException('PetFinder Request Failed', 503);
        }

        return $animals;
    }


    /**
     * Filter API request result into list of animals
     * 
     * @param  string     $results JSON blob from external API
     * @return array|null
     */
    protected function filterResult($results)
    {
        if (!$results) {
            return null;
        }
        $results = json_decode($results, true);

        if (isset($results['petfinder']['pets']['pet'])) {
            $animals = array_map(
                [$this, 'filterAnimal'],
                $results['petfinder']['pets']['pet']
            );
            $animals = array_values(array_filter($animals));
        } else {
            $animals = null;
        }

        return $animals;
    }


    /**
     * Converts a single result into a usable sanitized form
     * 
     * @param  array      $result
     * @return array|null         Incomplete or malformed result returns null
     *     string 'name' Animal name
     *     int    'id'   Animal id number
     *     string 'img'  Animal photo url
     */
    protected function filterAnimal($result)
    {
        $animal['name'] = $this->filter->Word($result['name']['$t']);
        // discard result with just an id and no proper name
        if (preg_match('/^A\d+$/', $animal['name'])) {
            return null;
        }

        $animal['number'] = $this->filter->Int($result['id']['$t']);

        $photos = (isset($result['media']['photos']['photo'])) ?
                  $result['media']['photos']['photo'] : null;
        // discard result with zero photos
        if (!$photos) {
            return null;
        }
        $xIndex = array_search('x', array_column($photos, '@size'));
        // discard result with no appropriate photo
        if ($xIndex === false) {
            return null;
        }
        $animal['img'] = $this->filter->Url($photos[$xIndex]['$t']);

        return $animal;
    }
}
