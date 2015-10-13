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

namespace cms\routes\Alertbanner\views;

/**
 * FormView for updating Alertbanner widget
 */
class UpdateForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'href' => [
            'label' => 'URL: <small>the page you want the banner to link to
                        </small>',
            'type' => 'text',
            'attr' => [
                'name' => 'href',
            ],
            'data-binding' => 'href',
            'required' => true,
            'sanitize' => [
                'filters' => [
                    'strtolower',
                    'word' => '\/\d'
                ],
            ],
        ],
        'title' => [
            'label' => 'Title: <small>the brief text you want displayed in the 
                        banner</small>',
            'type' => 'text',
            'attr' => [
                'name' => 'title',
            ],
            'data-binding' => 'title',
            'required' => true,
            'sanitize' => ['filters' => 'plaintext'],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Update Banner',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'alertbanner/update',
        'method'          => 'post',
        'data-valmethods' => 'reload-hide',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Edit Alert Banner';
    /**
     * Alertbanner repo instance
     * @var object
     */
    private $banner;


    function __construct(
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\Alertbanner $banner
    ) {
        parent::__construct($builder);
        $this->banner = $banner;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->banner->getBanner();
    }
}
