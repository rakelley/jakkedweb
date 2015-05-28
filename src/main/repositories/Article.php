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
namespace main\repositories;

/**
 * Main repository for articles
 */
class Article extends \rakelley\jhframe\classes\Repository
{
    /**
     * Article model instace
     * @var object
     */
    protected $articleModel;
    /**
     * IFilter library instance
     * @var object
     */
    protected $filter;
    /**
     * UserAccount repo instance
     * @var object
     */
    protected $userAccount;


    function __construct(
        UserAccount $userAccount,
        \rakelley\jhframe\interfaces\services\IFilter $filter,
        \main\models\Article $articleModel
    ) {
        $this->userAccount = $userAccount;
        $this->filter = $filter;
        $this->articleModel = $articleModel;
    }


    /**
     * Pass-through to get list of all authors
     * 
     * @return array|null
     */
    public function getAuthors()
    {
        return $this->userAccount->getAuthors();
    }


    /**
     * Get all articles
     * 
     * @return array|null
     */
    public function getAll()
    {
        return $this->articleModel->articles;
    }


    /**
     * Get single article
     * 
     * @param  int        $id Key for article
     * @return array|null
     */
    public function getArticle($id)
    {
        $this->articleModel->setParameters(['id' => $id]);
        $article = $this->articleModel->article;

        if ($article) {
            $article['photo'] =
                $this->userAccount->getPhoto($article['author']);
        }

        return $article;
    }

    /**
     * Update single existing article
     * 
     * @param  array $input
     * @return void
     */
    public function setArticle(array $input)
    {
        $this->articleModel->article = $input;
    }

    /**
     * Add a new article
     * 
     * @param  array $input
     * @return void
     */
    public function addArticle(array $input)
    {
        $this->articleModel->Add($input);
    }

    /**
     * Delete a single article
     * 
     * @param  int  $id Key for article
     * @return void
     */
    public function deleteArticle($id)
    {
        $this->articleModel->setParameters(['id' => $id]);
        unset($this->articleModel->article);
    }


    /**
     * Get paginated subset of articles
     * 
     * @param  int        $pageNum      Page number
     * @param  int        $itemsPerPage Number of articles per page
     * @return array|null
     */
    public function getPage($pageNum, $itemsPerPage)
    {
        $pointer = ($pageNum - 1) * $itemsPerPage;
        $this->articleModel->setParameters(['pointer' => $pointer,
                                            'results' => $itemsPerPage]);

        return $this->articleModel->articles;
    }


    /**
     * Get total number of pages when articles are paginated
     * 
     * @param  int $itemsPerPage Number of articles per page
     * @return int
     */
    public function getPageCount($itemsPerPage)
    {
        return (int) ceil($this->articleModel->count / $itemsPerPage);
    }


    /**
     * Converts the contents of an article into an abbreviated blurb without
     * non-text tags
     * 
     * @param  string $content
     * @return string
     */
    public function filterNews($content)
    {
        $permitted = '<p><a><ul><ol><li><br><h1><h2><h3><h4><h5><h6>';
        $content = strip_tags($content, $permitted);

        if (strlen($content) < 500) {
            $content = $this->filter->tidyText($content);
        } else {
            $content = substr($content, 0, 500);
            $content = $this->filter->tidyText($content);
            $content .= '...';
        }

        return $content;
    }


    /**
     * Reverts all articles written by $author to the default author
     *
     * @param  string $author
     * @return void
     */
    public function revertAuthor($author)
    {
        $this->articleModel->revertAuthor($author);
    }
}
