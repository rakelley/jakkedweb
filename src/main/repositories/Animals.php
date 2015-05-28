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

use \Psr\Log\LogLevel;

/**
 * Repository for animal shelter gallery data and images
 */
class Animals extends \rakelley\jhframe\classes\Repository
{
    use \rakelley\jhframe\traits\LogsExceptions,
        \rakelley\jhframe\traits\ServiceLocatorAware;

    /**
     * Animals model instance
     * @var object
     */
    protected $animalModel;
    /**
     * PetFinderAPI repo instance
     * @var object
     */
    protected $petfinder;
    /**
     * AnimalImageHandler instance
     * @var object
     */
    protected $imageHandler;


    function __construct(
        PetFinderAPI $petfinder,
        \main\AnimalImageHandler $imageHandler,
        \main\models\Animals $animalModel
    ) {
        $this->animalModel = $animalModel;
        $this->petfinder = $petfinder;
        $this->imageHandler = $imageHandler;
    }


    /**
     * Returns properties and photo for all current animals, if any
     * 
     * @return array|null
     */
    public function getAnimals()
    {
        $animals = $this->animalModel->animals;

        if ($animals) {
            $animals = array_map(
                function($animal) {
                    $animal['photo'] = $this->imageHandler->makeRelative(
                        $this->imageHandler->Read($animal['number'])
                    );
                    return $animal;
                },
                $animals
            );
        }

        return $animals;
    }


    /**
     * Blackbox self-update method.
     * Retrieves list from PetFinderAPI, diffs with current, performs clean
     * additions and deletions.
     * Should handle all thrown exceptions and allow higher context to handle
     * error reporting as appropriate.
     *
     * @return boolean True if update successful
     */
    public function Update()
    {
        try {
            $this->setLongDuration();

            $fetched = $this->petfinder->getLatest();
            $existing = $this->animalModel->animals;
            $diff = $this->createDiff($fetched, $existing);

            if ($diff['toAdd']) {
                $diff['toAdd'] = $this->addImages($diff['toAdd']);
                $this->animalModel->Add($diff['toAdd']);
            }

            if ($diff['toDelete']) {
                $this->animalModel->Delete($diff['toDelete']);
                array_walk($diff['toDelete'], [$this->imageHandler, 'Delete']);
            }

            return true;
        } catch (\RuntimeException $e) {
            $this->logException($e, LogLevel::WARNING);
            return false;
        } catch (\Exception $e) {
            $this->logException($e, LogLevel::CRITICAL);
            return false;
        }
    }


    /**
     * Internal method to create a diff between animal sets
     * 
     * @param  array      $newAnimals New list of animals with all properties
     * @param  array|null $oldAnimals Existing list of animals with all
     *                                properties
     * @return array
     *     array 'toAdd'    List of new animals with all properties, or empty
     *                      array if nothing to add
     *     array 'toDelete' List of ids of animals to delete, or empty array if
     *                      none
     */
    protected function createDiff(array $newAnimals, array $oldAnimals=null)
    {
        if (!$oldAnimals) {
            return ['toAdd' => $newAnimals, 'toDelete' => []];
        }

        $oldNums = array_column($oldAnimals, 'number');
        $newNums = array_column($newAnimals, 'number');

        $diff['toDelete'] = array_diff($oldNums, $newNums);

        $toAdd = array_diff($newNums, $oldNums);
        if ($toAdd) {
            $diff['toAdd'] = array_map(
                function($number) use ($newAnimals, $newNums) {
                    $index = array_search($number, $newNums);
                    return $newAnimals[$index];
                }, 
                $toAdd
            );
        } else {
            $diff['toAdd'] = [];
        }

        return $diff;
    }


    /**
     * Internal method to add images for new animals and return successful set
     * 
     * @param  array $animals All animals for whom an image should be set
     * @return array          Subset of $animals for whom image operation was
     *                        successful
     */
    protected function addImages(array $animals)
    {
        $animals = array_map(
            function($animal) {
                $success = $this->imageHandler->Write($animal['number'],
                                                      $animal['img']);
                if ($success) {
                    return [$animal['number'], $animal['name']];
                } else { 
                    //Drop animals whose image fails so they aren't added to DB
                    return null;
                }
            },
            $animals
        );

        return array_values(array_filter($animals));
    }


    /**
     * Abstraction wrapper for set_time_limit.
     * Since we're calling an external api and potentially curling a number of
     * images the operation may take a long time.
     */
    protected function setLongDuration()
    {
        set_time_limit(120);
    }
}
