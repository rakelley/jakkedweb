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
namespace main\routes\Testimonials\actions;

/**
 * FormAction to add a new customer testimonial
 */
class Add extends \rakelley\jhframe\classes\FormAction
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\action\ValidatesBotcheckField;

    /**
     * TestimonialQueue repo instance
     * @var object
     */
    private $queue;


    /**
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\routes\Testimonials\views\AddForm              $view
     * @param \main\repositories\TestimonialQueue                  $queue
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\routes\Testimonials\views\AddForm $view,
        \main\repositories\TestimonialQueue $queue
    ) {
        $this->queue = $queue;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $input['date'] = date('Y-m-d H:i:s');
        $input['content'] = <<<HTML
<p>{$this->input['textarea']}</p>
<p> ---{$this->input['name']}</p>
HTML;

        $this->queue->addTestimonial($input);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        $this->validateBotcheckField();
    }
}
