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
 * Repository containing metadata for all pages of the main site
 */
class PageData extends \rakelley\jhframe\classes\Repository implements
    \rakelley\jhframe\interfaces\repository\IMetaData
{
    /**
     * Instance of Page model, set by constructor
     * @var object
     */
    private $pageModel;


    /**
     * @param \main\models\Page $pageModel
     */
    function __construct(
        \main\models\Page $pageModel
    ) {
        $this->pageModel = $pageModel;
    }


    /**
     * Get all pages
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->pageModel->pages;
    }


    /**
     * Get subset of pages belonging to group
     * 
     * @param  string $group Name of group
     * @return array|null
     */
    public function getGroup($group)
    {
        return $this->pageModel->getGroup($group);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\repository\IMetaData::getPage()
     */
    public function getPage($route)
    {
        $this->pageModel->setParameters(['route' => $route]);
        return $this->pageModel->page;
    }

    /**
     * Set metadata for page
     *
     * @param  array $input
     * @return void
     */
    public function setPage(array $input)
    {
        $this->pageModel->page = $input;
    }

    /**
     * Add a new page
     *
     * @param  array $input
     * @return void
     */
    public function addPage(array $input)
    {
        $this->pageModel->Add($input);
    }

    /**
     * Delete data for a page
     *
     * @param  string $route Route for page
     * @return void
     */
    public function deletePage($route)
    {
        $this->pageModel->setParameters(['route' => $route]);
        unset($this->pageModel->page);
    }
}
