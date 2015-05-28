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
 * Repository for queued Testimonials
 */
class TestimonialQueue extends Testimonials implements \main\IQueueRepository
{

    function __construct(
        \main\models\TestimonialQueue $queue
    ) {
        parent::__construct($queue);
    }
}
