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

namespace cms\routes\Plrecords\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for adding a powerlifting meet
 */
class AddMeet extends \rakelley\jhframe\classes\FormAction
{
    /**
     * PlMeets repo instance
     * @var object
     */
    private $meets;


    /**
     * @param \cms\routes\Plrecords\views\AddMeetForm              $view
     * @param \cms\repositories\PlMeets                            $meets
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     */
    function __construct(
        \cms\routes\Plrecords\views\AddMeetForm $view,
        \cms\repositories\PlMeets $meets,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator
    ) {
        $this->meets = $meets;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->meets->addMeet($this->input);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (in_array($this->input['meet'], $this->meets->getAll())) {
            throw new InputException('Meet Already Exists');
        }
    }
}
