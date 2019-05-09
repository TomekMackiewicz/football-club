<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * Games for pagination
     * 
     * @param int $size
     * @param string $sort
     * @param string $order
     * @param int $offset
     * @param array $filters
     * @return Game[]
     */
    public function findGames(int $size, string $sort, string $order, int $offset, array $filters)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('g')->from('App:Game', 'g');
        
        if ($filters['dateFrom']) {
            $qb->andWhere('g.date >= :dateFrom')
                ->setParameter(":dateFrom", $filters['dateFrom']);
        }

        if ($filters['dateTo']) {
            $qb->andWhere('g.date <= :dateTo')
                ->setParameter(":dateTo", $filters['dateTo']);
        }
        
        if ($filters['location']) {
            $qb->andWhere('g.location LIKE :location')
                ->setParameter(":location", '%'.$filters['location'].'%');
        }

        if ($filters['gameType']) {
            $qb->andWhere('g.gameType = :gameType')
                ->setParameter(":gameType", $filters['gameType']);
        }

        if ($filters['team']) {
            $qb->andWhere('g.hostTeam = :team OR g.guestTeam = :team')
                ->setParameter(":team", $filters['team']);
        }
        
        $qb->orderBy('g.'.$sort, $order)
            ->setMaxResults($size)
            ->setFirstResult($offset);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Count games
     * @return integer
     */
    public function countGames()
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->getQuery()
            ->getSingleScalarResult();        
    }
    
    /**
     * Find games with given id's
     * @param array $ids
     * @return array
     */
    public function findGamesByIds(array $ids)
    {
        return $this->createQueryBuilder('g')
            ->where("g.id IN(:ids)")
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();        
    }

}
