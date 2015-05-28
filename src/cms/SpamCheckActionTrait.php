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

namespace cms;

use \rakelley\jhframe\classes\InputException;

/**
 * Trait for Actions which need to validate a spamcheck field
 */
trait SpamCheckActionTrait
{

    /**
     * Config store getter, can be implemented by \rakelley\jhframe\traits\ConfigAware
     */
    abstract protected function getConfig();


    /**
     * Validation method
     *
     * @param string $input  The value to check if passes
     * @param bool   $return Whether to return success value instead of throwing
     * @return void|bool
     * @throws \rakelley\jhframe\classes\InputException on failure if !$return
     */
    protected function validateSpamCheck($input, $return=false)
    {
        $values = explode(',',
                          $this->getConfig()->Get('SECRETS', 'HUMANVERIFY'));

        $success = in_array($input, $values);
        if ($return) {
            return $success;
        } elseif (!$success) {
            throw new InputException('Spamcheck Verification Failed');
        }
    }
}
