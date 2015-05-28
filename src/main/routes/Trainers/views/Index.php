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
namespace main\routes\Trainers\views;

/**
 * View for Trainers
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData,
    \rakelley\jhframe\interfaces\view\IHasMetaData
{
    use \rakelley\jhframe\traits\view\GetsMetaData,
        \rakelley\jhframe\traits\ServiceLocatorAware;

    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * Trainers repo instance
     * @var object
     */
    private $trainers;


    /**
     * @param \main\repositories\Trainers $trainers
     */
    function __construct(
        \main\repositories\Trainers $trainers
    ) {
        $this->trainers = $trainers;

        $this->metaRoute = 'trainers/';
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->trainers->getVisible();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        if ($this->data) {
            $trainers = $this->fillTrainers($this->data);
        } else {
            $trainers = <<<HTML
<div class="trainer_gallery-trainer">
    <h2 class="heading-section-minor">Our Trainers Are Still Filling Out Their
        Profiles, Check Back Soon
    </h2>
</div>

HTML;
        }

        $this->viewContent = <<<HTML
<section class="trainer_gallery-heading">
    <h1 class="page-heading">Our Trainers</h2>
    <p>Whatever your needs, whatever your experience level, we have a trainer
        for you.
    </p>
    <p>Trainers aren't just for teaching new exercises or making up workout
        plans. They help you stay focused and motivated in your workouts, can
        help you with your diet, and they provide accountability that will keep
        you sticking to your plans. Most of our trainers work with trainers
        too!
    </p>
</section>

{$trainers}

<section class="trainer_gallery-footer">
    <p>Think you have something to offer the Jakked Hardcore training staff? 
        We're always on the lookout for new talent, and offer competitive flat 
        rates and a well-equipped facility for qualified independent trainers
        looking for a new home.
    </p>
</section>

HTML;
    }


    /**
     * Generates markup for block of all trainers
     * 
     * @param  array  $trainers List of trainers
     * @return string
     */
    private function fillTrainers(array $trainers)
    {
        $trainers = array_map(
            function($index, $trainer) {
                $profile = $this->fillProfile($trainer);
                // open row section on even index, close on odd
                if (!($index % 2)) {
                    return '<section class="column-row">' . $profile;
                } else {
                    return $profile . '</section>';
                }
            },
            array_keys($trainers),
            array_values($trainers)
        );
        // closes row section if last index was even
        if (count($trainers) % 2) {
            $trainers[count($trainers) - 1] .= '</section>';
        }

        return implode('', $trainers);
    }


    /**
     * Generates markup for a single trainer
     * 
     * @param  array  $trainer Trainer properties
     * @return string
     */
    private function fillProfile(array $trainer)
    {
        if ($trainer['photo']) {
            $photo = <<<HTML
<img src="{$trainer['photo']}" alt="{$trainer['name']}" />
HTML;
        } else {
            $photo = '<div class="trainer_gallery-image-placeholder"></div>';
        }

        $profile = ($trainer['profile']) ?: 
                   '<p>This trainer has not filled out their profile yet!</p>';

        return <<<HTML
<div class="trainer_gallery-trainer">
    {$photo}
    <h2 class="heading-section-minor">{$trainer['name']}</h2>
    {$profile}
</div>
HTML;
    }
}
