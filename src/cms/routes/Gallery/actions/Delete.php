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

namespace cms\routes\Gallery\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * Action for deleting a gallery
 */
class Delete extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Gallery repo instance
     * @var object
     */
    private $galleries;


    /**
     * @param \cms\routes\Gallery\views\DeleteForm                 $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\Gallery                           $galleries
     */
    function __construct(
        \cms\routes\Gallery\views\DeleteForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\Gallery $galleries
    ) {
        $this->galleries = $galleries;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->galleries->deleteGallery($this->input['gallery']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->galleries->galleryExists($this->input['gallery'])) {
            throw new InputException('Gallery Not Found');
        }
    }
}
