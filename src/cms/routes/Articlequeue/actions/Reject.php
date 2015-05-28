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

namespace cms\routes\Articlequeue\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for rejecting queued articles
 */
class Reject extends \rakelley\jhframe\classes\FormAction
{
    /**
     * ArticleQueue repo instance
     * @var object
     */
    private $queue;


    /**
     * @param \cms\routes\Articlequeue\views\DeleteForm            $view
     * @param \cms\repositories\ArticleQueue                       $queue
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     */
    function __construct(
        \cms\routes\Articlequeue\views\DeleteForm $view,
        \cms\repositories\ArticleQueue $queue,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator
    ) {
        $this->queue = $queue;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->queue->deleteArticle($this->input['id']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->queue->getArticle($this->input['id'])) {
            throw new InputException('Article Not Found in Queue');
        }
    }
}
