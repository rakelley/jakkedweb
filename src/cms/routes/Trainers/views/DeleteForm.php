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

namespace cms\routes\Trainers\views;

/**
 * FormView for deleting a trainer
 */
class DeleteForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'name' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'name',
                'value' => '%name%',
            ],
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s']],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Delete',
                'class' => 'button_dangerous',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'class'           => 'form-invisible',
        'action'          => 'trainers/delete',
        'method'          => 'post',
        'data-valmethods' => 'reload-hide',
        'data-confirm'    => '',
    ];
}
