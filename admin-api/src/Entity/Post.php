<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Category;
use App\Entity\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @UniqueEntity(
 *     fields={"slug"},
 *     message="validation.unique"
 * )
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message = "validation.required")
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @Assert\NotBlank(message = "validation.required")
     * @Assert\Regex(
     *   pattern = "/^[a-z0-9]+(?:-[a-z0-9]+)*$/",
     *   match = true,
     *   message = "validation.slug"
     * )
     * @ORM\Column(name="slug", type="string", unique=true)
     */
    private $slug;    
    
    /**
     * @Assert\NotBlank(message = "validation.required")
     * @ORM\Column(name="body", type="text")
     */
    private $body;   
    
    /**
     * @ORM\Column(name="publish_date", type="datetime", nullable=true)
     */
    private $publishDate;

    /**
     * @ORM\Column(name="modify_date", type="datetime", nullable=true)
     */
    private $modifyDate;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinTable(name="posts_categories")
     */
    private $categories;    
    
    /**
     * @ORM\ManyToMany(targetEntity="File")
     * @ORM\JoinTable(name="posts_images",
     *   joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="cascade")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="cascade")}
     * )
     */
    private $images;    

    public function __construct() {
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /** 
     * @return int
     */    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return \self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }    

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return \self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }     

    /**
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return \self
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }  

    /**
     * @return DateTime|null
     */
    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    /**
     * @param \DateTimeInterface $publishDate
     * @return \self
     */
    public function setPublishDate(\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getModifyDate(): ?\DateTimeInterface
    {
        return $this->modifyDate;
    }

    /**
     * @param \DateTimeInterface $modifyDate
     * @return \self
     */
    public function setModifyDate(\DateTimeInterface $modifyDate): self
    {
        $this->modifyDate = $modifyDate;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories(): ?ArrayCollection
    {
        return $this->categories;
    }
    
    /**
     * @param Category $category
     */
    public function addCategory(Category $category)
    {        
        $this->categories->add($category);
    }
    
    /**
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    } 

    /**
     * @return ArrayCollection
     */
    public function getImages(): ?ArrayCollection
    {
        return $this->images;
    }
    
    /**
     * @param \App\Entity\File $image
     */
    public function addImage(File $image)
    {
        $this->images->add($image);
    }
    
    /**
     * @param File $image
     */
    public function removeImage(File $image)
    {
        $this->images->removeElement($image);
    }
    
}
