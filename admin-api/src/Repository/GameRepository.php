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
     * @return Game[]
     */
    public function findGames(int $size, string $sort, string $order, int $offset)
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.'.$sort, $order)
            ->setMaxResults($size)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
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
            ->setParameter('ids', $ids['ids'])
            ->getQuery()
            ->getResult();        
    }

}
