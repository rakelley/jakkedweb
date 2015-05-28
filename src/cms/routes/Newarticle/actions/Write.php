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

namespace cms\routes\Newarticle\actions;

/**
 * FormAction to create a new article
 */
class Write extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Article repo instance
     * @var object
     */
    private $article;
    /**
     * AuthService instance
     * @var object
     */
    private $auth;
    /**
     * ArticleQueue repo instance
     * @var object
     */
    private $queue;


    /**
     * @param \cms\routes\Newarticle\views\NewForm                 $view
     * @param \cms\repositories\ArticleQueue                       $queue
     * @param \rakelley\jhframe\interfaces\services\IAuthService   $auth
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\Article                           $article
     */
    function __construct(
        \cms\routes\Newarticle\views\NewForm $view,
        \cms\repositories\ArticleQueue $queue,
        \rakelley\jhframe\interfaces\services\IAuthService $auth,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\Article $article
    ) {
        $this->queue = $queue;
        $this->auth = $auth;
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
        $this->input['date'] = date('Y-m-d H:i:s');
        $this->input['author'] = $this->auth->getUser('username');
        $this->input['content'] = $this->filterContent($this->input['content']);

        // If user is an Editor, add article directly, otherwise add to queue
        if ($this->auth->checkPermission('editarticle')) {
            $this->article->addArticle($this->input);
        } else {
            $this->queue->addArticle($this->input);
        }
    }

    /**
     * Hacks to work around limitations of TinyMCE
     * 
     * @param  string $content
     * @return string
     */
    private function filterContent($content)
    {
        $content = str_replace('style="text-align: center;"',
                               'class="alignment-center"',
                               $content);
        $content = str_replace('&nbsp;', '', $content);

        return $content;
    }
}
