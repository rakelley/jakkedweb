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

namespace cms\routes\Testimonialqueue\views;

/**
 * QueueIndexView for testimonial queue
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \cms\QueueIndexViewTrait;


    function __construct(
        \main\repositories\TestimonialQueue $testimonials
    ) {
        $this->queueRepo = $testimonials;

        $this->queueName = 'Testimonial';
        $this->queueController = 'testimonialqueue';
    }
}
