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

namespace cms\routes\Trainers\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for deleting a trainer
 */
class Delete extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Trainers repo instance
     * @var object
     */
    private $trainers;


    /**
     * @param \cms\routes\Trainers\views\DeleteForm                $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\Trainers                          $trainers
     */
    function __construct(
        \cms\routes\Trainers\views\DeleteForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\Trainers $trainers
    ) {
        $this->trainers = $trainers;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->trainers->deleteTrainer($this->input['name']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->trainers->getTrainer($this->input['name'])) {
            throw new InputException('Trainer Not Found');
        }
    }
}
