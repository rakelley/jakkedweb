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

namespace cms\routes\Files\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for file uploads
 */
class Add extends \rakelley\jhframe\classes\FormAction implements
    \rakelley\jhframe\interfaces\action\IHasResult
{
    use \rakelley\jhframe\traits\ConfigAware,
        \rakelley\jhframe\traits\ServiceLocatorAware;

    /**
     * Site base URL, fetched from config
     * @var string
     */
    protected $basePath;
    /**
     * Appropriate lazy-loaded FileHandler service
     * @var object
     */
    protected $handler;
    /**
     * Class name for handler for TYPE_ARTICLE
     * @var string
     */
    protected $handlerArticle = '\cms\ArticleImageHandler';
    /**
     * Class name for handler for TYPE_GALLERY
     * @var string
     */
    protected $handlerGallery = '\main\GalleryImageHandler';
    /**
     * Class name for handler for TYPE_PDF
     * @var string
     */
    protected $handlerPdf = '\cms\PdfHandler';
    /**
     * Key to use in writing file
     * @var string
     */
    protected $key;
    /**
     * Path to application public directory, fetched from config
     * @var string
     */
    protected $publicDir;
    /**
     * Constant for supported filetype image for article
     */
    const TYPE_ARTICLE = 'articleimage';
    /**
     * Constant for supported filetype image for gallery
     */
    const TYPE_GALLERY = 'galleryimage';
    /**
     * Constant for supported filetype pdf
     */
    const TYPE_PDF = 'pdf';


    function __construct(
        \cms\routes\Files\views\AddForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator
    ) {
        $this->basePath = $this->getConfig()->Get('APP', 'base_path');
        $this->publicDir = $this->getConfig()->Get('ENV', 'public_dir');

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->handler->Write($this->key, $this->input['file']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IHasResult::getResult()
     * @return string
     */
    public function getResult()
    {
        $path = $this->handler->Read($this->key);
        $path = str_replace(
            $this->publicDir,
            str_replace('cms.', '', $this->basePath),
            $path
        );

        $result = <<<HTML
<label>Your file URL:
    <input type="text" value="{$path}" />
</label>
HTML;

        return $result;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        $this->prepareHandler();

        if ($this->handler->Read($this->key) &&
            !isset($this->input['overwrite'])
        ) {
            throw new InputException('File Already Exists');
        }

        $this->handler->Validate($this->input['file']);
    }


    /**
     * Prepares FileHandler service and key based on filetype.  Implementation
     * must support all types for which constants have been provided.
     * 
     * @return void
     */
    protected function prepareHandler()
    {
        switch ($this->input['filetype']) {
            case $this::TYPE_PDF:
                $handler = $this->handlerPdf;
                $key = $this->normalizeName($this->input['file']['name']);
                break;

            case $this::TYPE_ARTICLE:
                $handler = $this->handlerArticle;
                $key = $this->normalizeName($this->input['file']['name']);
                break;

            case $this::TYPE_GALLERY:
                if (!isset($this->input['gallery'])) {
                    throw new InputException('No Gallery Selected');
                }
                $handler = $this->handlerGallery;
                $key = $this->normalizeName($this->input['file']['name']);
                $key = $this->input['gallery'] . '/' . $key;
                break;

            default:
                throw new InputException('Invalid File Type Selected');
                break;
        }

        $this->handler = $this->getLocator()->Make($handler);
        $this->key = $key;
    }


    /**
     * Shared method for standardizing file names
     * 
     * @param  string $name Provided file name
     * @return string       Altered file name
     */
    protected function normalizeName($name)
    {
        $name = strtolower(str_replace(' ', '_', $name));
        $name = preg_replace('/[^\w.]/i', '', $name);
        if (substr($name, 0, 4) !== date('Y')) {
            $name = date('Y_m_d') . '_' . $name;
        }

        return $name;
    }
}
