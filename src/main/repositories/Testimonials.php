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
 * Repository for interacting with customer testimonials
 */
class Testimonials extends \rakelley\jhframe\classes\Repository implements
    \rakelley\jhframe\interfaces\repository\IRandomAccess
{
    use \rakelley\jhframe\traits\PickRandomArrayElements;

    /**
     * Testimonials model instance
     * @var object
     */
    protected $testiModel;


    /**
     * @param \main\models\Testimonials $testiModel
     */
    function __construct(
        \main\models\Testimonials $testiModel
    ) {
        $this->testiModel = $testiModel;
    }


    /**
     * Fetch all testimonials
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->testiModel->testimonials;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\repository\IRandomAccess::getRandom()
     */
    public function getRandom($count)
    {
        $tests = $this->getAll();
        if (!$tests) {
            return null;
        }

        $result = $this->pickRandomArrayElements($tests, $count);

        return array_column($result, 'content');
    }


    /**
     * Get a single testimonial
     * 
     * @param  int        $id Key
     * @return array|null
     */
    public function getTestimonial($id)
    {
        $this->testiModel->setParameters(['id' => $id]);
        return $this->testiModel->testimonial;
    }

    /**
     * Add a single testimonial
     * 
     * @param  array $input
     * @return void
     */
    public function addTestimonial(array $input)
    {
        $this->testiModel->Add($input);
    }

    /**
     * Delete a single testimonial
     * 
     * @param  int  $id Key
     * @return void
     */
    public function deleteTestimonial($id)
    {
        $this->testiModel->setParameters(['id' => $id]);
        unset($this->testiModel->testimonial);
    }
}
