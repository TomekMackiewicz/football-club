<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigRepository")
 */
class Config
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex(
     *   pattern="/[^0-9]/",
     *   match=false,
     *   message="validation.digits"
     * )
     */
    private $smallFileSize;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex(
     *   pattern="/[^0-9]/",
     *   match=false,
     *   message="validation.digits"
     * )
     */
    private $mediumFileSize;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex(
     *   pattern="/[^0-9]/",
     *   match=false,
     *   message="validation.digits"
     * )
     */
    private $largeFileSize;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return \self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSmallFileSize(): ?int
    {
        return $this->smallFileSize;
    }

    /**
     * @param int $smallFileSize
     * @return \self
     */
    public function setSmallFileSize(int $smallFileSize): self
    {
        $this->smallFileSize = $smallFileSize;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMediumFileSize(): ?int
    {
        return $this->mediumFileSize;
    }

    /**
     * @param int $mediumFileSize
     * @return \self
     */
    public function setMediumFileSize(int $mediumFileSize): self
    {
        $this->mediumFileSize = $mediumFileSize;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLargeFileSize(): ?int
    {
        return $this->largeFileSize;
    }

    /**
     * @param int $largeFileSize
     * @return \self
     */
    public function setLargeFileSize(int $largeFileSize): self
    {
        $this->largeFileSize = $largeFileSize;

        return $this;
    }
}