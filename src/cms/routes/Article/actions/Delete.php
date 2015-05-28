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

namespace cms\routes\Article\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for deleting an article
 */
class Delete extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Article repo instance
     * @var object
     */
    private $article;


    /**
     * @param \cms\routes\Article\views\DeleteForm                 $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\Article                           $article
     */
    function __construct(
        \cms\routes\Article\views\DeleteForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\Article $article
    ) {
        $this->article = $article;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     * @return void
     */
    public function Proceed()
    {
        $this->article->deleteArticle($this->input['id']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->article->getArticle($this->input['id'])) {
            throw new InputException('Article Not Found');
        }
    }
}
