<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingRepository")
 * @CustomAssert\ContainsOverlapingTrainingDateForLocation
 */
class Training
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="validation.required")
     */
    private $startDate; 

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="validation.required")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="validation.required")
     */
    private $location;

    /**
     * @ORM\ManyToMany(targetEntity="Trainer", inversedBy="trainings")
     * @ORM\JoinTable(name="training_trainer")
     * @Assert\NotBlank(message="validation.required")
     */
    private $trainers;    

    public function __construct() {
        //$this->teams = new ArrayCollection();
        $this->trainers = new ArrayCollection();
    } 
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }
    
    /**
     * @return Collection
     */
    public function getTrainers(): ?Collection
    {
        return $this->trainers;
    }
    
    /**
     * @param Trainer $trainer
     */
    public function addTrainer(Trainer $trainer)
    {        
        $this->trainers->add($trainer);
    }
    
    /**
     * @param Trainer $trainer
     */
    public function removeTrainer(Trainer $trainer)
    {
        $this->trainers->removeElement($trainer);
    } 
}
