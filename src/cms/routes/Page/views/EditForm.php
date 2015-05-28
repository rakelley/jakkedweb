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

namespace cms\routes\Page\views;

/**
 * FormView for editing a page
 */
class EditForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'route' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'route',
            ],
            'data-binding' => 'name',
            'required' => true,
            'sanitize' => ['filters' => ['strtolower', 'word']],
        ],
        'description' => [
            'type' => 'textarea',
            'label' => 'Description: <small>This will be displayed with the page
                        in search engines</small>',
            'attr' => [
                'name' => 'description',
                'class' => 'textarea_small valDescription',
            ],
            'data-binding' => 'description',
            'required' => true,
            'sanitize' => 'default',
        ],
        'title' => [
            'type' => 'text',
            'label' => 'Title: <small>This is the page title that will be 
                        displayed to visitors.  Should be 2-3 words and less
                        than 30 characters to allow for keyword usage</small>',
            'attr' => [
                'name' => 'title',
                'class' => 'valPageTitle',
            ],
            'data-binding' => 'title',
            'required' => true,
            'sanitize' => [
                'filters' => 'encodeHtml',
                'maxlength' => 75,
            ],
        ],
        'priority' => [
            'type' => 'text',
            'label' => 'Priority: <small>How important the page is relative to
                        other pages on the site, from 0.0 (least) to 1.0 (most)
                        </small>',
            'attr' => [
                'name' => 'priority',
            ],
            'data-binding' => 'priority',
            'required' => true,
            'sanitize' => ['filters' => 'float'],
        ],
        'content' => [
            'type' => 'textarea',
            'label' => 'Content: <small>Should be plain HTML.  If you just want
                        to change the wording of something, be sure not to touch
                        any of the surrounding tags.</small>',
            'attr' => [
                'name' => 'content',
                'class' => 'acetarget',
                'data-acetarget' => '',
            ],
            'data-binding' => 'content',
            'required' => true,
            'sanitize' => '',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Update Page',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'page/edit',
        'method'          => 'post',
        'data-valmethods' => 'redirect-show',
        'data-redirect'   => 'page/',
    ];
    /**
     * FlatPage repo instance
     * @var object
     */
    private $page;


    function __construct(
        \cms\repositories\FlatPage $page,
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder
    ) {
        parent::__construct($builder);
        $this->page = $page;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->page->getPage($this->parameters['name']);
    }
}
