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

namespace cms\routes\Testimonialqueue\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for rejecting a queued testimonial
 */
class Reject extends \rakelley\jhframe\classes\FormAction
{
    /**
     * TestimonialQueue repo instance
     * @var object
     */
    private $queue;


    /**
     * @param \cms\routes\Testimonialqueue\views\RejectForm        $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\TestimonialQueue                  $queue
     */
    function __construct(
        \cms\routes\Testimonialqueue\views\RejectForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\TestimonialQueue $queue
    ) {
        $this->queue = $queue;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     * @return void
     */
    public function Proceed()
    {
        $this->queue->deleteTestimonial($this->input['id']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->queue->getTestimonial($this->input['id'])) {
            throw new InputException('Item Not Found in Queue');
        }
    }
}
