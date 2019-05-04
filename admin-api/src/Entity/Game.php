<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message="validation.required")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="validation.required")
     */
    private $location;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="validation.required")
     */
    private $gameType;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="validation.required")
     */
    private $hostTeam;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="validation.required")
     */
    private $guestTeam;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="validation.required")
     * @Assert\Regex(
     *     pattern="/[^0-9]/",
     *     match=false,
     *     message="validation.digits"
     * )
     */
    private $hostScore;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="validation.required")
     * @Assert\Regex(
     *     pattern="/[^0-9]/",
     *     match=false,
     *     message="validation.digits"
     * )
     */
    private $guestScore;

    /** 
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @return DateTime|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     * @return \self
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return \self
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGameType(): ?int
    {
        return $this->gameType;
    }

    /**
     * @param int $type
     * @return \self
     */
    public function setGameType(int $type): self
    {
        $this->gameType = $type;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHostTeam(): ?int
    {
        return $this->hostTeam;
    }

    /**
     * @param int $hostTeam
     * @return \self
     */
    public function setHostTeam(int $hostTeam): self
    {
        $this->hostTeam = $hostTeam;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGuestTeam(): ?int
    {
        return $this->guestTeam;
    }

    /**
     * @param int $guestTeam
     * @return \self
     */
    public function setGuestTeam(int $guestTeam): self
    {
        $this->guestTeam = $guestTeam;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHostScore(): ?int
    {
        return $this->hostScore;
    }

    /**
     * @param int $hostScore
     * @return \self
     */
    public function setHostScore(int $hostScore): self
    {
        $this->hostScore = $hostScore;

        return $this;
    }
    
    /**
     * @return int|null
     */
    public function getGuestScore(): ?int
    {
        return $this->guestScore;
    }

    /**
     * @param int $guestScore
     * @return \self
     */
    public function setGuestScore(int $guestScore): self
    {
        $this->guestScore = $guestScore;

        return $this;
    }
}
