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
 * Repository for Quotes
 */
class Quotes extends \rakelley\jhframe\classes\Repository implements
    \rakelley\jhframe\interfaces\repository\IRandomAccess
{
    use \rakelley\jhframe\traits\PickRandomArrayElements;

    /**
     * Quote model instance
     * @var object
     */
    protected $quoteModel;


    /**
     * @param \main\models\Quotes $quoteModel
     */
    function __construct(
        \main\models\Quotes $quoteModel
    ) {
        $this->quoteModel = $quoteModel;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\repository\IRandomAccess::getRandom()
     */
    public function getRandom($count)
    {
        $quotes = $this->getAll();
        if (!$quotes) {
            return null;
        }

        $result = $this->pickRandomArrayElements($quotes, $count);

        return array_column($result, 'quote');
    }


    /**
     * Return all quotes
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->quoteModel->quotes;
    }


    /**
     * Add a new quote
     * 
     * @param  string $quote Quote to add
     * @return void
     */
    public function addQuote($quote)
    {
        $this->quoteModel->Add($quote);
    }


    /**
     * Delete one or more quotes matching id(s)
     * 
     * @param  array  $ids
     * @return void
     */
    public function deleteQuotes(array $ids)
    {
        $this->quoteModel->Delete($ids);
    }
}
