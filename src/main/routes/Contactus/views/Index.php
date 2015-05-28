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
namespace main\routes\Contactus\views;

/**
 * Composite index view with contact information
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IHasMetaData,
    \rakelley\jhframe\interfaces\view\IHasSubViews
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\GetsMetaData,
        \rakelley\jhframe\traits\view\MakesSubViews;


    function __construct()
    {
        $this->metaRoute = 'contactus/';
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $map = $this->fillMap();

        $this->viewContent = <<<HTML
<div class="column-row">
    <section class="column-nine">
        <h1 class="heading-page">Contact Us</h1>
        <p>Address: 1450 S. East River Rd, Unit D, Montgomery, IL 60538</p>
        <p>Phone: 630.966.8611</p>
        <p>Email: 
            <a href="mailto:gym@jakkedhardcore.com">gym@jakkedhardcore.com</a>
        </p>
        <p>Have a quick question or concern?  Feel free to contact us and we'll
            do our best to get back to you ASAP.  If you have more involved
            questions about fitness, training, or our facility, we'd love to
            speak to you in person, so please stop by anytime.
        </p>
    </section>

    {$map}
</div>

<section class="column-nine requires-js">
    {$this->subViews['contactForm']}
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
            'contactForm' => 'ContactForm',
        ];
    }


    /**
     * Generates markup for iframe Google map
     * 
     * @return string
     */
    private function fillMap()
    {
        $iframeSrc = '//maps.google.com/maps?f=q&source=s_q&hl=en&geocode='
                   . '&q=Jakked+Hardcore+Gym,+1450+Southeast+River+Road,+Montgo'
                   . 'mery,+IL+60538&aq=0&oq=jakk&sll=41.727912,-88.338511&sspn'
                   . '=0.003359,0.004399&vpsrc=0&ie=UTF8&hq=Jakked+Hardcore+Gym'
                   . ',&hnear=1450+SE+River+Rd,+Montgomery,+Illinois+60538&t=h&'
                   . 'll=41.727976,-88.339305&spn=0.005125,0.006866&z=16&iwloc='
                   . 'A&output=embed';
        $mapLink = '//maps.google.com/maps?f=q&source=embed&hl=en&geocode='
                 . '&q=Jakked+Hardcore+Gym,+1450+Southeast+River+Road,+Montgome'
                 . 'ry,+IL+60538&aq=0&oq=jakk&sll=41.727912,-88.338511&sspn=0.0'
                 . '03359,0.004399&vpsrc=0&ie=UTF8&hq=Jakked+Hardcore+Gym,&hnea'
                 . 'r=1450+SE+River+Rd,+Montgomery,+Illinois+60538&t=h&ll=41.72'
                 . '7976,-88.339305&spn=0.005125,0.006866&z=16&iwloc=A';

        return <<<HTML
<section class="column-seven requires-js">
    <h2 class="heading-section">Map</h2>
    <iframe class="gmap" frameborder="0" scrolling="no" marginheight="0"
        marginwidth="0" src="{$iframeSrc}">
    </iframe>
    <p class="para-footnote">
        <a href="{$mapLink}" title="Go to Google Maps">View Larger Map</a>
    </p>
</section>
HTML;
    }
}
