<?php

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
     * Game games for paginator
     * 
     * @param int $size
     * @param string $sort
     * @param string $order
     * @param int $offset
     * @return Game[]
     */
    public function findGames($size, $sort, $order, $offset)
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.'.$sort, $order)
            ->setMaxResults($size)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

}
