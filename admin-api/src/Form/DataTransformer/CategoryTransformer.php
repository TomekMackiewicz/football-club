<?php

namespace App\Form\DataTransformer;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CategoryTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object to a number
     *
     * @param  ArrayCollection|null $collection
     * @return integer
     */
    public function transform($collection)
    {
        if (empty($collection)) {
            return null;
        }
        
        $categories = $collection->toArray();
        
        $ids = [];        
        foreach ($categories as $category) {
            $ids[] = $category->getId();
        }
 
        return $ids;
    }

    /**
     * Transforms an integer to an object
     *
     * @param  array
     * @return array|null
     * @throws TransformationFailedException if object is not found
     */
    public function reverseTransform($ids)
    {
        if (empty($ids[0])) {
            return [];
        }
        
        $categories = $this->entityManager->getRepository(Category::class)->findById($ids);
        
        if (empty($categories[0])) {
            throw new TransformationFailedException(sprintf(
                'Categories does not exists',
                $categories
            ));
        }
        
        return $categories;
    }
}
