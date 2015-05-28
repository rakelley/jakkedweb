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
 * FormView for creating a new page
 */
class NewForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'route' => [
            'type' => 'text',
            'label' => 'Name: <small>The URL of the page will be
                        jakkedhardcore.com/name</small>',
            'attr' => [
                'name' => 'route',
                'class' => 'valNewPage',
            ],
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
                'value' => '0.5',
            ],
            'required' => true,
            'sanitize' => ['filters' => 'float'],
        ],
        'content' => [
            'type' => 'textarea',
            'label' => 'Content: <small>The HTML body of the page, will be
                        wrapped in a sixteen column div.</small>',
            'attr' => [
                'name' => 'content',
                'class' => 'acetarget',
                'data-acetarget' => '',
            ],
            'required' => true,
            'sanitize' => '',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Create',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'page/add',
        'method'          => 'post',
        'data-valmethods' => 'replace-show',
        'data-redirect'   => 'page/',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Create A New Static Page';


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = $this->standardConstructor();
        $this->viewContent .= <<<HTML
<script data-main="js/src/cms/main" data-page="lib/aceInit" src="js/require.js">
</script>

HTML;
    }
}
