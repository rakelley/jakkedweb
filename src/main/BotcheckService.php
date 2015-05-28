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

namespace main;

use \rakelley\jhframe\classes\InputException;

/**
 * Implementation of IBotcheck for jakkedweb
 */
class BotcheckService implements \rakelley\jhframe\interfaces\services\IBotcheck
{
    /**
     * Input service instance
     * @var object
     */
    private $input;
    /**
     * Expected value for phone field
     * @var integer
     */
    private $phoneValue = 5555555555;


    /**
     * @param \rakelley\jhframe\interfaces\services\IInput $input
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IInput $input
    ) {
        $this->input = $input;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\services\IBotcheck::getField()
     */
    public function getField()
    {
        return <<<HTML
<input type="hidden" name="phone" value="{$this->phoneValue}" aria-hidden="true"
    aria-disabled="true" />
<label for="company" class="hidden" aria-hidden="true">If you can see
    this, leave the company field blank.</label>
<input type="text" name="company" class="hidden" aria-hidden="true"
    aria-disabled="true"/>

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\services\IBotcheck::validateField()
     */
    public function validateField()
    {
        $list = [
            'company' => '',
            'phone' => ['filters' => 'int']
        ];
        $input = $this->input->getList($list, 'post', true);

        if (isset($input['company']) || !isset($input['phone']) ||
            $input['phone'] !== $this->phoneValue)
        {
            throw new InputException('Invalid Input in a Special Field');
        }
    }
}
