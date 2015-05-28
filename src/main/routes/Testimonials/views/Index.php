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
namespace main\routes\Testimonials\views;

/**
 * Testimonial view
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData,
    \rakelley\jhframe\interfaces\view\IHasMetaData,
    \rakelley\jhframe\interfaces\view\IHasSubViews
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\GetsMetaData,
        \rakelley\jhframe\traits\view\MakesSubViews;


    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * Testimonials repo instance
     * @var object
     */
    private $testimonials;
    /**
     * Number of testimonials to show
     * @var int
     */
    private $testCount = 3;


    /**
     * @param \main\repositories\Testimonials $testimonials
     */
    function __construct(
        \main\repositories\Testimonials $testimonials
    ) {
        $this->testimonials = $testimonials;

        $this->metaRoute = 'testimonials/';
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->testimonials->getRandom($this->testCount);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $testimonials = implode('', array_map(
            [$this, 'fillTestimonial'],
            $this->data
        ));

        $this->viewContent = <<<HTML
<div class="column-sixteen margin-bottom-double">
    <h1 class="heading-page">Member Testimonials</h1>
</div>

<section class="column-eight">
    {$testimonials}
</section>

<section class="column-eight requires-js">
    <h3>Has Jakked Changed Your Life?</h3>
    {$this->subViews['addForm']}
</section>

<script data-main="js/src/main/main" data-page="lib/postForm"
    src="js/require.js"></script>

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'addForm' => 'AddForm',
        ];
    }


    /**
     * Generates markup for a single testimonial
     * 
     * @param  string $content Testimonial
     * @return string
     */
    private function fillTestimonial($content)
    {
        return '<section class="margin-bottom">' . $content . '</section>';
    }
}
