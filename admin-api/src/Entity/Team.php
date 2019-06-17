<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @UniqueEntity(
 *   fields={"year", "name"},
 *   message="validation.unique"
 * )
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "validation.required")
     * @Assert\Regex(
     *   pattern = "^[12][0-9]{3}$^",
     *   match = true,
     *   message = "validation.digits"
     * )
     */
    private $year;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $belongsToUser;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $playsLeague;

    /**
     * @ORM\ManyToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="logo", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $logo;

//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    private $players;
//
//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    private $games;

    /**
     * @ORM\ManyToMany(targetEntity="Training", mappedBy="teams", cascade={"persist"})
     */
    private $trainings;

    public function __construct() 
    {
        //$this->players = new ArrayCollection();
        //$this->games = new ArrayCollection();
        $this->trainings = new ArrayCollection();
    }    
    
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** 
     * @param string $name
     * @return \self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** 
     * @return int|null
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return \self
     */
    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getBelongsToUser(): ?bool
    {
        return $this->belongsToUser;
    }

    /**
     * @param bool|null $belongsToUser
     * @return \self
     */
    public function setBelongsToUser(?bool $belongsToUser): self
    {
        $this->belongsToUser = $belongsToUser;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPlaysLeague(): ?bool
    {
        return $this->playsLeague;
    }

    /**
     * @param bool|null $playsLeague
     * @return \self
     */
    public function setPlaysLeague(?bool $playsLeague): self
    {
        $this->playsLeague = $playsLeague;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getLogo(): ?File
    {
        return $this->logo;
    }

    /**
     * @param File $logo
     * @return \self
     */
    public function setLogo(File $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

//    public function getPlayers(): ?string
//    {
//        return $this->players;
//    }
//
//    public function setPlayers(string $players): self
//    {
//        $this->players = $players;
//
//        return $this;
//    }
//
//    public function getGames(): ?string
//    {
//        return $this->games;
//    }
//
//    public function setGames(string $games): self
//    {
//        $this->games = $games;
//
//        return $this;
//    }

    /** 
     * @return Collection|null
     */
    public function getTrainings(): ?Collection
    {
        return $this->trainings;
    }

//    public function setTrainings(string $trainings): self
//    {
//        $this->trainings = $trainings;
//
//        return $this;
//    }
}
