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

namespace cms\routes\Files\views;

use \cms\routes\Files\actions\Add;

/**
 * FormView for file uploads
 */
class AddForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'MAX_FILE_SIZE' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'MAX_FILE_SIZE',
                'value' => '5000000',
            ],
        ],
        'file' => [
            'label' => 'Upload images or files to include in article posts or
                        for upcoming events.  Accepted files are .pdf, .gif,
                        .jpg, and .png.  Images larger than 1mB and PDFs
                        larger than 5mB will fail.',
            'type' => 'file',
            'attr' => [
                'name' => 'file',
                'class' => 'valFile',
            ],
        ],
        'filetype' => [
            'type' => 'select',
            'attr' => [
                'name' => 'filetype',
                'id' => 'filetype',
            ],
            'options' => [//should match action const list
                'empty' => 'Select a File Type',
                Add::TYPE_PDF => 'PDF',
                Add::TYPE_ARTICLE => 'Image for Article',
                Add::TYPE_GALLERY => 'Image for Gallery',
            ],
            'required' => true,
            'sanitize' => [
                'filters' => ['word', 'strtolower'],
            ],
        ],
        'gallery' => [
            'type' => 'select',
            'attr' => [
                'name' => 'gallery',
                'id' => 'gallery',
                'class' => 'hidden',
            ],
            'options' => [
                'empty' => 'Destination Gallery',
            ],
            'data-binding' => 'galleries',
            'sanitize' => [
                'filters' => ['word', 'strtolower'],
            ],
        ],
        'overwrite' => [
            'type' => 'checkbox',
            'label' => 'Overwrite existing files of the same name?',
            'attr' => [
                'name' => 'overwrite',
                'value' => 'true',
            ],
            'sanitize' => '',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Upload',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'files/add',
        'method'          => 'post',
        'enctype'         => 'multipart/form-data',
        'data-valmethods' => 'replace-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Upload A File';
    /**
     * Gallery repo instance
     * @var object
     */
    private $galleries;


    /**
     * @param \rakelley\jhframe\interfaces\services\IFormBuilder $builder
     * @param \main\repositories\Gallery                         $galleries
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\Gallery $galleries
    ) {
        parent::__construct($builder);
        $this->galleries = $galleries;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data['galleries'] = $this->galleries->getAll();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = $this->standardConstructor();
        $this->viewContent .= <<<HTML
<script data-main="js/src/cms/main" data-page="cms/routes/Files/addform"
src="js/require.js"></script>

HTML;
    }
}
