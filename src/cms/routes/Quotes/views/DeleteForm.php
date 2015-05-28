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

namespace cms\routes\Quotes\views;

/**
 * FormView for deleting one or more quotes
 */
class DeleteForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'quotes' => [
            'method' => 'fillQuotes',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Delete Quote(s)',
                'class' => 'button_dangerous',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'quotes/delete',
        'method'          => 'post',
        'data-valmethods' => 'reload-hide',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Delete One or More Quotes';
    /**
     * Quotes repo instance
     * @var object
     */
    private $quotes;


    /**
     * @param \rakelley\jhframe\interfaces\services\IFormBuilder $builder
     * @param \main\repositories\Quotes                          $quotes
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\Quotes $quotes
    ) {
        parent::__construct($builder);
        $this->quotes = $quotes;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->quotes->getAll();
    }


    /**
     * Custom field method for generating quote markup
     * 
     * @return string
     */
    protected function fillQuotes()
    {
        return implode('', array_map(
            function($row) {
                return <<<HTML
<label>
    <input type="checkbox" name="quote{$row['id']}" value="{$row['id']}" />
    {$row['quote']}
</label>
HTML;
            },
            $this->data
        ));
    }
}
