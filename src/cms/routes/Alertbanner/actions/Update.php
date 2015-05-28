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

namespace cms\routes\Alertbanner\actions;

/**
 * Action for updating the alertbanner widget
 */
class Update extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Alertbanner repo instance
     * @var object
     */
    private $banner;


    /**
     * @param \cms\routes\Alertbanner\views\UpdateForm             $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\Alertbanner                       $banner
     */
    function __construct(
        \cms\routes\Alertbanner\views\UpdateForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\Alertbanner $banner
    ) {
        $this->banner = $banner;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     * @return void
     */
    public function Proceed()
    {
        $this->banner->setBanner($this->input);
    }
}
