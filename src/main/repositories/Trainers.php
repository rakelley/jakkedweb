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
 * Repository for interacting with trainer profiles
 */
class Trainers extends \rakelley\jhframe\classes\Repository
{
    /**
     * TrainerImageHandler instance
     * @var object
     */
    protected $photoHandler;
    /**
     * Trainer model instance
     * @var object
     */
    protected $trainerModel;


    function __construct(
        \main\TrainerImageHandler $photoHandler,
        \main\models\Trainers $trainerModel
    ) {
        $this->photoHandler = $photoHandler;
        $this->trainerModel = $trainerModel;
    }


    /**
     * Get all trainers
     * 
     * @return array|null
     */
    public function getAll()
    {
        return $this->trainerModel->alltrainers;
    }


    /**
     * Get photo for a trainer
     * 
     * @param  string      $name Trainer key
     * @return string|null       Relative path to trainer image
     */
    public function getPhoto($name)
    {
        $path = $this->photoHandler->Read($this->getPhotoKey($name));

        return ($path) ? $this->photoHandler->makeRelative($path) : null;
    }

    /**
     * Get photo for a trainer
     * 
     * @param  string $name  Trainer key
     * @param  mixed  $value Null to unset photo, array or string to set new
     * @return void
     */
    public function setPhoto($name, $value)
    {
        if ($value) {
            $this->photoHandler->Write($this->getPhotoKey($name), $value);    
        } else {
            $this->photoHandler->Delete($this->getPhotoKey($name));
        }       
    }

    /**
     * Validate uploaded image for use
     * 
     * @see \rakelley\jhframe\interfaces\IFileHandler::Validate()
     */
    public function validatePhoto(array $file)
    {
        return $this->photoHandler->Validate($file);
    }

    /**
     * Internal method for coverting trainer key to token for image
     * 
     * @param  string $name
     * @return string
     */
    protected function getPhotoKey($name)
    {
        return strtolower(str_replace(' ', '_', $name));
    }


    /**
     * Get a single trainer
     * 
     * @param  string     $name Trainer key
     * @return array|null
     */
    public function getTrainer($name)
    {
        $this->trainerModel->setParameters(['name' => $name]);
        $trainer = $this->trainerModel->trainer;

        if ($trainer) {
            $trainer['photo'] = $this->getPhoto($name);
        }

        return $trainer;
    }

    /**
     * Update a single trainer
     * 
     * @param  array $input
     * @return void
     */
    public function setTrainer(array $input)
    {
        $this->trainerModel->setParameters(['name' => $input['name']]);
        $this->trainerModel->trainer = $input;
    }

    /**
     * Add a single trainer
     * 
     * @param  array $input
     * @return void
     */
    public function addTrainer($input)
    {
        $this->trainerModel->Add($input);
    }

    /**
     * Delete a single trainer
     * 
     * @param  string $name Trainer key
     * @return void
     */
    public function deleteTrainer($name)
    {
        $this->setPhoto($name, null);
        $this->trainerModel->Delete($name);
    }


    /**
     * Get subset of all trainers who are marked visible
     * 
     * @return array|null
     */
    public function getVisible()
    {
        $trainers = $this->trainerModel->allvisible;

        if ($trainers) {
            $trainers = array_map(
                function($trainer) {
                    $trainer['photo'] = $this->getPhoto($trainer['name']);
                    return $trainer;
                },
                $trainers
            );
        }

        return $trainers;
    }
}
