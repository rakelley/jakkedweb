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

namespace cms\routes\Validate\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * Action to verify page matching route doesn't exist
 */
class PageNotExists extends \rakelley\jhframe\classes\Action
{
    use \rakelley\jhframe\traits\GetsInput,
        \rakelley\jhframe\traits\ServiceLocatorAware;

    /**
     * FlatPage repo instance
     * @var object
     */
    private $page;


    /**
     * @param \cms\repositories\FlatPage $page
     */
    function __construct(
        \cms\repositories\FlatPage $page
    ) {
        $this->page = $page;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     * @return bool
     */
    public function Proceed()
    {
        $requires = ['route' => ['filters' => ['word', 'strtolower']]];
        try {
            $input = $this->getInput($requires, 'post');
            $result = !$this->page->getPage($input['route']);
        } catch (InputException $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $result;
    }
}
