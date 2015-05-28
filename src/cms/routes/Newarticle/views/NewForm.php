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

namespace cms\routes\Newarticle\views;

/**
 * FormView for writing a new article
 */
class NewForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'title' => [
            'label' => 'Title: <small>Title must be less than 200 characters.
                        </small>',
            'type' => 'text',
            'attr' => [
                'name' => 'title',
                'class' => 'valArticleTitle',
                'placeholder' => 'Enter the article title',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'tags' => [
            'label' => 'Tags: <small>Tags are optional and must be comma
                        separated</small>',
            'type' => 'text',
            'attr' => [
                'name' => 'tags',
                'placeholder' => 'Add tags',
            ],
            'sanitize' => 'default',
        ],
        'content' => [
            'label' => 'Article Body: <small>Basic HTML is allowed but do not
                        use style tags or inline css.</small>',
            'type' => 'textarea',
            'attr' => [
                'name' => 'content',
                'class' => 'valTextarea',
                'placeholder' => 'Place the article body here',
                'data-tinymce' => '',
            ],
            'required' => true,
            'sanitize' => ['filters' => 'tidytext'],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Publish',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'newarticle/write',
        'method'          => 'post',
        'data-valmethods' => 'show-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = [
        'title' => 'Write A New Article',
        'sub' => 'If you are not an approved Editor, articles will be
                  submitted to a queue for approval first.',
    ];


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $success = 'Article Published Successfully';
        $this->viewContent = $this->standardConstructor($success);
        $this->viewContent .= <<<HTML
<script data-main="js/src/cms/main" data-page="lib/mceInit" src="js/require.js">
</script>

HTML;
    }
}
