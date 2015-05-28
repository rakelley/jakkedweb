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

namespace cms\routes\Nav\views;

/**
 * FormView for adding a new nav entry
 */
class AddForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'route' => [
            'type' => 'text',
            'label' => 'Route: <small>the path to the page</small>',
            'attr' => [
                'name' => 'route',
                'placeholder' => 'example/page',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'title' => [
            'type' => 'text',
            'label' => 'Title: <small>the text shown on the nav button or list
                        for the page</small>',
            'attr' => [
                'name' => 'title',
                'placeholder' => 'Example Page',
            ],
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s']],
        ],
        'description' => [
            'type' => 'text',
            'label' => 'Description: <small>A description of the page\'s 
                        content that will be shown to users hovering over the
                        link</small>',
            'attr' => [
                'name' => 'description',
                'class' => 'valDescription',
                'placeholder' => 'This is an Example Page',
            ],
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s']],
        ],
        'isparent' => [//Visual cue for user, value is discarded
            'type' => 'checkbox',
            'label' => 'Top-Level Entry?',
            'attr' => [
                'name' => 'isparent',
                'id' => 'isparent',
                'value' => 'isparent',
            ],
        ],
        'parent' => [
            'type' => 'select',
            'label' => 'Parent: <small>The desired parent entry</small>',
            'attr' => [
                'name' => 'parent',
                'id' => 'parent',
            ],
            'options' => ['empty' => 'None'],
            'data-binding' => 'parents',
            'sanitize' => ['filters' => ['word' => '\s']],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Create New Entry',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'nav/add',
        'method'          => 'post',
        'data-valmethods' => 'reload-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Add a New Entry';
    /**
     * Navigation repo instance
     * @var object
     */
    private $navEntries;


    /**
     * @param \rakelley\jhframe\interfaces\services\IFormBuilder $builder
     * @param \main\repositories\Navigation                      $navEntries
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\Navigation $navEntries
    ) {
        parent::__construct($builder);
        $this->navEntries = $navEntries;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data['parents'] = $this->navEntries->getParentList();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = $this->standardConstructor();
        $this->viewContent .= <<<HTML
<script data-main="js/src/cms/main" data-page="cms/routes/Nav/addform"
src="js/require.js"></script>

HTML;
    }
}
