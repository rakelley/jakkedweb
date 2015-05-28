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

namespace cms\routes\Article\views;

/**
 * FormView for editing articles
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
        'id' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'id',
            ],
            'data-binding' => 'id',
            'required' => true,
            'sanitize' => 'default',
        ],
        'date' => [
            'label' => 'Date: <small>must be YYYY-MM-DD HH:MM:SS format</small>',
            'type' => 'text',
            'attr' => [
                'name' => 'date',
                'class' => 'valDateTime',
            ],
            'data-binding' => 'date',
            'required' => true,
            'sanitize' => 'default',
        ],
        'author' => [
            'label' => 'Author: ',
            'type' => 'select',
            'attr' => [
                'name' => 'author',
            ],
            'data-binding' => 'authors',
            'selected-data' => 'author',
            'required' => true,
            'sanitize' => ['filters' => 'email'],
        ],
        'title' => [
            'label' => 'Title: ',
            'type' => 'text',
            'attr' => [
                'name' => 'title',
                'class' => 'valArticleTitle',
            ],
            'data-binding' => 'title',
            'required' => true,
            'sanitize' => 'default',
        ],
        'tags' => [
            'label' => 'Tags: <small>optional</small>',
            'type' => 'text',
            'attr' => [
                'name' => 'tags',
            ],
            'data-binding' => 'tags',
            'sanitize' => 'default',
        ],
        'content' => [
            'label' => 'Article Body: <small>HTML is allowed but do not use
                        style tags or inline css.</small>',
            'type' => 'textarea',
            'attr' => [
                'name' => 'content',
                'class' => 'valTextarea',
                'data-tinymce' => '',
            ],
            'data-binding' => 'content',
            'required' => true,
            'sanitize' => ['filters' => 'tidytext'],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Update Article',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'article/edit',
        'method'          => 'post',
        'data-valmethods' => 'redirect-hide',
        'data-redirect'   => 'article/',
    ];
    /**
     * Article repo instance
     * @var object
     */
    private $article;


    /**
     * @param \rakelley\jhframe\interfaces\services\IFormBuilder $builder
     * @param \main\repositories\Article                         $article
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\Article $article
    ) {
        parent::__construct($builder);
        $this->article = $article;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->article->getArticle($this->parameters['id']);
        if (!$this->data) {
            throw new \DomainException(
                'Article ' . $this->parameters['id'] . ' Not Found',
                404
            );
        }

        $this->data['authors'] = $this->article->getAuthors();
    }
}
