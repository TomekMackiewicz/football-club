<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Training;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TrainingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Training::class);
    }

    /**
     * Trainings for pagination
     * 
     * @param int $size
     * @param string $sort
     * @param string $order
     * @param int $offset
     * @param array $filters
     * @return Training[]
     */
    public function findTrainings(int $size, string $sort, string $order, int $offset, array $filters)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('t')->from('App:Training', 't');
        
        if (!empty($filters) && $filters['location']) {
            $qb->andWhere('t.location LIKE :location')
                ->setParameter(":location", '%'.$filters['location'].'%');
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
    public function countTrainings()
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();        
    }    

    /**
     * Find trainings with given id's
     * 
     * @param array $ids
     * @return array
     */
    public function findTrainingsByIds(array $ids)
    {
        return $this->createQueryBuilder('t')
            ->where("t.id IN(:ids)")
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();        
    }
    
    /**
     * Helper function for training validation
     * 
     * @param Training $training
     * @return array|null
     */
    public function findTrainingWithOverlapingDates($training)
    {
        $id = $training->getId();
        $startDate = $training->getStartDate();
        $endDate = $training->getEndDate();
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select('t')->from('App:Training', 't');        
        $qb->andWhere('t.endDate >= :from AND t.startDate <= :to')
           ->setParameter('from', $startDate)
           ->setParameter('to', $endDate);

        // To omit same training during update
        if (null !== $id) {
            $qb->andWhere("t.id !=:id")->setParameter('id', $id);
        }
        
        return $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();        
    }
}
