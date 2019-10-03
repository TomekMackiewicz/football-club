<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Trainer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TrainerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trainer::class);
    }

    /**
     * Trainers for pagination
     * 
     * @param int $size
     * @param string $sort
     * @param string $order
     * @param int $offset
     * @param array $filters
     * @return Trainer[]
     */
    public function findTrainers(int $size, string $sort, string $order, int $offset, array $filters)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('t')->from('App:Trainer', 't');
        
        if (!empty($filters) && $filters['firstName']) {
            $qb->andWhere('t.firstName LIKE :firstName')
                ->setParameter(":firstName", '%'.$filters['firstName'].'%');
        }
        
        if (!empty($filters) && $filters['lastName']) {
            $qb->andWhere('t.lastName LIKE :lastName')
                ->setParameter(":lastName", '%'.$filters['lastName'].'%');
        }

        if (!empty($filters) && $filters['email']) {
            $qb->andWhere('t.email LIKE :email')
                ->setParameter(":email", '%'.$filters['email'].'%');
        }
        
        if (!empty($sort) && !empty($order)) {
           $qb->orderBy('t.'.$sort, $order);
        }
               
        if (!empty($size)) {
           $qb->setMaxResults($size); 
        }
        
        if (!empty($offset)) {
           $qb->setFirstResult($offset);
        }            
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * @return integer
     */
    public function countTrainers()
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();        
    }    

    /**
     * Find trainers with given id's
     * @param array $ids
     * @return array
     */
    public function findTrainersByIds(array $ids)
    {
        return $this->createQueryBuilder('t')
            ->where("t.id IN(:ids)")
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();        
    }
}
