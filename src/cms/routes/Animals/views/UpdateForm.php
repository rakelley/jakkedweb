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

namespace cms\routes\Animals\views;

/**
 * FormView for updating animal gallery
 */
class UpdateForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'submit' => [
            'type' => 'submit',
            'label' => 'This gallery should automatically update nightly, but
                        you can use this button to force a manual update.
                        Updating may take some time.',
            'attr' => [
                'value' => 'Update Animal Gallery',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'animals/update',
        'method'          => 'post',
        'data-valmethods' => 'show-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Update Animal Gallery';


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $success = 'Gallery Updated!';
        $this->viewContent = $this->standardConstructor($success);
    }
}
