<?php
declare(strict_types=1);

namespace App\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToObjectTransformer implements DataTransformerInterface
{
    private $entityManager;
    private $class;

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->class = $class;
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
//ob_start();
//var_dump('class');
//var_dump(Category::class);
//var_dump($this->class);
//$textualRepresentation = ob_get_contents();
//ob_end_clean();
//file_put_contents("/var/www/html/log.log", $textualRepresentation);         
        $categories = $this->entityManager->getRepository($this->class)->findById($ids);
        
        if (empty($categories[0])) {
            throw new TransformationFailedException(sprintf(
                'Categories does not exists',
                $categories
            ));
        }
        
        return $categories;
    }
}
