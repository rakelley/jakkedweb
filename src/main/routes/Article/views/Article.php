<?php
/**
 * @package jakkedweb
 * @subpackage main
 * 
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */
namespace main\routes\Article\views;

/**
 * View for an individual article
 */
class Article extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData,
    \rakelley\jhframe\interfaces\view\IHasMetaData
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * Article repo instance
     * @var object
     */
    private $article;
    /**
     * Store for fetched data
     * @var array
     */
    private $data;


    /**
     * @param \main\repositories\Article $article
     */
    function __construct(
        \main\repositories\Article $article
    ) {
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
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IHasMetaData::fetchMetaData()
     */
    public function fetchMetaData()
    {
        $description = 'An article brought to you by Jakked Hardcore Gym.';
        if ($this->data['tags']) {
            $description .= ' Tags: ' . $this->data['tags'];
        }

        $this->metaData = [
            'title' => $this->data['title'],
            'description' => $description
        ];
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $article = $this->fillArticle($this->data);
        $modal = $this->fillModal($this->data);

        $this->viewContent = $article . $modal;
    }


    /**
     * Generates markup for main body of article
     * 
     * @param  array $article Article data
     * @return string
     */
    private function fillArticle($article)
    {
        $date = date('F jS Y', strtotime($article['date']));

        return <<<HTML
<article class="article_page">
    <h1 class="article_page-title">{$article['title']}</h1>
        <p class="article_page-attribution">
            {$date}<br/>
            <a data-modal="#author" href="#" rel="author">
                {$article['fullname']}
            </a>
        </p>
        {$article['content']}
</article>

<section class="article_page-footer">
    <p class="article_page-tags">Tags: {$article['tags']}</p>
</section>
HTML;
    }


    /**
     * Generates markup for article author modal
     * 
     * @param  array $author Author data
     * @return string
     */
    private function fillModal($author)
    {
        if (!empty($author['photo'])) {
            $photo = <<<HTML
<img class="article_page-author-photo" src="{$author['photo']}" />
HTML;
        } else {
            $photo = '';
        }

        if (!empty($author['profile'])) {
            $profile = $author['profile'];
        } else {
            $profile = "<p>This Author Hasn't Created A Profile Yet</p>";
        }

        return <<<HTML
<div id="author" class="article_page-author" title="{$author['fullname']}"
    data-modal-window>
    {$photo}
    {$profile}
</div>
<script data-main="js/src/main/main" data-page="lib/modals"
    src="js/require.js"></script>
HTML;
    }
}
