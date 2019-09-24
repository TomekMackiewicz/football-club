<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Users for pagination
     * 
     * @param int $size
     * @param string $sort
     * @param string $order
     * @param int $offset
     * @param array $filters
     * @return User[]
     */
    public function findUsers(int $size, string $sort, string $order, int $offset, array $filters)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')->from('App:User', 'u');
        
        if ($filters['username']) {
            $qb->andWhere('u.username LIKE :username')
                ->setParameter(":username", '%'.$filters['username'].'%');
        }

        if ($filters['email']) {
            $qb->andWhere('u.email = :email')
                ->setParameter(":email", $filters['email']);
        }
        
        $qb->orderBy('u.'.$sort, $order)
            ->setMaxResults($size)
            ->setFirstResult($offset);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * @return integer
     */
    public function countUsers()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();        
    }
    
    /**
     * Find users with given id's
     * @param array $ids
     * @return array
     */
    public function findUsersByIds(array $ids)
    {
        return $this->createQueryBuilder('u')
            ->where("u.id IN(:ids)")
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();        
    }

}
