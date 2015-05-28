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
 * Spamchecking implemented as a standalone Action.
 */
class SpamCheck extends \rakelley\jhframe\classes\Action
{
    use \cms\SpamCheckActionTrait,
        \rakelley\jhframe\traits\ConfigAware,
        \rakelley\jhframe\traits\GetsInput,
        \rakelley\jhframe\traits\ServiceLocatorAware;


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     * @return boolean
     */
    public function Proceed()
    {
        $requires = ['spamcheck' => ['filters' => ['word', 'strtolower']]];

        try {
            $input = $this->getInput($requires, 'post');
            $result = $this->validateSpamCheck($input['spamcheck'], true);
        } catch (InputException $e) {
            $result = false;
        }

        if (!$result) {
            $this->error = 'Spamcheck Verification Failed';
        }

        return $result;
    }
}
