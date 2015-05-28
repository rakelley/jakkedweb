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

namespace cms\routes\Animals\actions;

/**
 * Action for calling Animals repo self-update method
 */
class Update extends \rakelley\jhframe\classes\Action
{
    /**
     * Animals repo instance
     * @var object
     */
    private $animals;


    /**
     * @param \main\repositories\Animals $animals
     */
    function __construct(
        \main\repositories\Animals $animals
    ) {
        $this->animals = $animals;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     * @return bool
     */
    public function Proceed()
    {
        $success = $this->animals->Update();

        if (!$success) {
            $this->error = 'Update Failed, Please Try Again Later';
        }

        return $success;
    }
}
