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
 * Repository for the Alertbanner widget
 */
class Alertbanner extends \rakelley\jhframe\classes\Repository
{
    /**
     * Alertbanner model instance
     * @var object
     */
    protected $bannerModel;


    function __construct(
        \main\models\Alertbanner $bannerModel
    ) {
        $this->bannerModel = $bannerModel;
    }


    /**
     * Get banner properties
     * 
     * @return array
     */
    public function getBanner()
    {
        return $this->bannerModel->banner;
    }

    /**
     * Set banner properties
     * 
     * @param  array $value
     * @return void
     */
    public function setBanner($value)
    {
        $this->bannerModel->banner = $value;
    }
}
