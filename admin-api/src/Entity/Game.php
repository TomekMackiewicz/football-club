<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $hostTeam;

    /**
     * @ORM\Column(type="integer")
     */
    private $guestTeam;

    /**
     * @ORM\Column(type="integer")
     */
    private $hostScore;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $guestScore;

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getHostTeam()
    {
        return $this->hostTeam;
    }

    public function setHostTeam(int $hostTeam): self
    {
        $this->hostTeam = $hostTeam;

        return $this;
    }

    public function getGuestTeam()
    {
        return $this->guestTeam;
    }

    public function setGuestTeam(int $guestTeam): self
    {
        $this->guestTeam = $guestTeam;

        return $this;
    }

    public function getHostScore()
    {
        return $this->hostScore;
    }

    public function setHostScore(int $hostScore): self
    {
        $this->hostScore = $hostScore;

        return $this;
    }
    
    public function getGuestScore()
    {
        return $this->guestScore;
    }

    public function setGuestScore(int $guestScore): self
    {
        $this->guestScore = $guestScore;

        return $this;
    }
}
