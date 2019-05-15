<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Posts for pagination
     * 
     * @param int $size
     * @param string $sort
     * @param string $order
     * @param int $offset
     * @param array $filters
     * @return Post[]
     */
    // sort=date&order=desc&page=1&size=10&filters={"dateFrom":null,"dateTo":null,"title":""
    public function findPosts(int $size, string $sort, string $order, int $offset, array $filters)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('p')->from('App:Post', 'p');
        
        if ($filters['dateFrom']) {
            $qb->andWhere('p.publishDate >= :dateFrom')
                ->setParameter(":dateFrom", $filters['dateFrom']);
        }

        if ($filters['dateTo']) {
            $qb->andWhere('p.publishDate <= :dateTo')
                ->setParameter(":dateTo", $filters['dateTo']);
        }
        
        if ($filters['title']) {
            $qb->andWhere('p.title LIKE :title')
                ->setParameter(":title", '%'.$filters['title'].'%');
        }
        
        $qb->orderBy('p.'.$sort, $order)
            ->setMaxResults($size)
            ->setFirstResult($offset);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * @return integer
     */
    public function countPosts()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();        
    }    

    /**
     * Find posts with given id's
     * @param array $ids
     * @return array
     */
    public function findPostsByIds(array $ids)
    {
        return $this->createQueryBuilder('p')
            ->where("p.id IN(:ids)")
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();        
    }
    
}
