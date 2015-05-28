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

namespace cms\routes\Account\views;

/**
 * View for user account self-deletion form
 */
class DeleteForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     * @var array
     */
    protected $fields = [
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Delete Me',
                'class' => 'button_dangerous',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     * @var array
     */
    protected $attributes = [
        'action'          => 'account/delete',
        'method'          => 'post',
        'data-valmethods' => 'redirect-hide',
        'data-redirect'   => 'auth/logout',
        'data-confirm'    => '',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     * @var string
     */
    protected $title = 'Delete My Account.  This Action Cannot Be Undone.';
}
