<?php
/**
 * @package jakkedweb
 * @subpackage cms
 * 
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

namespace cms\repositories;

/**
 * Repository for CRUDing Flat views from the main app
 */
class FlatPage extends \rakelley\jhframe\classes\Repository
{
    /**
     * FileHandler for view files instance
     * @var object
     */
    private $fileHandler;
    /**
     * PageData repo instance
     * @var object
     */
    private $pageData;
    /**
     * Name of group that handled pages belong to
     * @var string
     */
    private $pageGroup = 'flat';


    function __construct(
        \cms\FlatPageHandler $fileHandler,
        \main\repositories\PageData $pageData
    ) {
        $this->fileHandler = $fileHandler;
        $this->pageData = $pageData;
    }


    /**
     * Get a list of all flat pages
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->pageData->getGroup($this->pageGroup);
    }


    /**
     * Gets properties for a single page
     * 
     * @param  string     $name Key for page
     * @return array|null
     */
    public function getPage($name)
    {
        $page = $this->pageData->getPage($name);

        if ($page) {
            $page['content'] = $this->fileHandler->Read($name);
        }

        return $page;
    }


    /**
     * Updates a single page
     * 
     * @param  array $input Page properties
     * @return void
     */
    public function setPage(array $input)
    {
        $this->fileHandler->Write($input['route'], $input['content']);
        unset($input['content']);

        $input['pagegroup'] = $this->pageGroup;
        $this->pageData->setPage($input);
    }


    /**
     * Adds a new page
     * 
     * @param  array $input Page properties
     * @return void
     */
    public function addPage(array $input)
    {
        $this->fileHandler->Write($input['route'], $input['content']);
        unset($input['content']);

        $input['pagegroup'] = $this->pageGroup;
        $this->pageData->addPage($input);
    }


    /**
     * Deletes a single page
     * 
     * @param  string $name Key for page
     * @return void
     */
    public function deletePage($name)
    {
        $this->fileHandler->Delete($name);

        $this->pageData->deletePage($name);
    }
}
