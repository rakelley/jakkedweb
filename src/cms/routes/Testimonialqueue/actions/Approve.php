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
 * FormAction for approving a queued testimonial
 */
class Approve extends \rakelley\jhframe\classes\FormAction
{
    /**
     * TestimonialQueue repo instance
     * @var object
     */
    private $queue;
    /**
     * Testimonials repo instance
     * @var object
     */
    private $testimonials;


    /**
     * @param \cms\routes\Testimonialqueue\views\ApproveForm       $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\TestimonialQueue                  $queue
     * @param \main\repositories\Testimonials                      $testimonials
     */
    function __construct(
        \cms\routes\Testimonialqueue\views\ApproveForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\TestimonialQueue $queue,
        \main\repositories\Testimonials $testimonials
    ) {
        $this->queue = $queue;
        $this->testimonials = $testimonials;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     * @return void
     */
    public function Proceed()
    {
        $queueId = $this->input['id'];
        unset($this->input['id']);

        $this->input['date'] = date('Y-m-d H:i:s');
        $this->testimonials->addTestimonial($this->input);

        $this->queue->deleteTestimonial($queueId);
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
