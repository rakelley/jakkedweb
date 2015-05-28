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
namespace main;

/**
 * Extends framework implementation of FlatView with metadata
 */
class FlatView extends \rakelley\jhframe\classes\FlatView implements
    \rakelley\jhframe\interfaces\view\IHasMetaData
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\GetsMetaData;


    /**
     * Should mirror parent implementation other than setting metaRoute
     *
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FlatView::setParameters()
     */
    public function setParameters(array $parameters=null)
    {
        if (!isset($parameters['view']) || !isset($parameters['namespace'])) {
            throw new \BadMethodCallException(
                'Required View argument Not Provided',
                500
            );
        }
        $this->file = $this->mapNameToFile($parameters['view'],
                                           $parameters['namespace']);

        $this->metaRoute = $parameters['view'];
    }
}
