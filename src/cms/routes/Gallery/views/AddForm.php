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

namespace cms\routes\Gallery\views;

/**
 * FormView for adding new gallery
 */
class AddForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'name' => [
            'type' => 'text',
            'label' => 'Name: <small>The name of the gallery, cannot contain 
                        spaces or special characters other than underscores
                        </small>',
            'attr' => [
                'name' => 'name',
                'class' => 'valName',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'title' => [
            'type' => 'text',
            'label' => 'Title: <small>The page title for the gallery.  Should be
                        2-3 words and less than 30 characters to allow for
                        keyword usage</small>',
            'attr' => [
                'name' => 'title',
                'class' => 'valPageTitle',
            ],
            'required' => true,
            'sanitize' => [
                'filters' => 'encodeHtml',
                'maxlength' => 75,
            ],
        ],
        'description' => [
            'type' => 'text',
            'label' => 'Description: <small>The page description for the gallery
                        </small>',
            'attr' => [
                'name' => 'description',
                'class' => 'valDescription',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'MAX_FILE_SIZE' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'MAX_FILE_SIZE',
                'value' => '500000',
            ],
        ],
        'indeximage' => [
            'type' => 'file',
            'label' => 'Index Image: <small>The image that will be displayed for
                        this gallery in the gallery index. Must be a .jpg, .png,
                        or .gif smaller than 500kb</small>',
            'attr' => [
                'name' => 'indeximage',
            ],
            'required' => true,
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Create New Gallery',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'gallery/add',
        'method'          => 'post',
        'enctype'         => 'multipart/form-data',
        'data-valmethods' => 'reload-hide',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Add A New Gallery';
}
