<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Config;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ConfigRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Config::class);
    }

    public function getConfig()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')->from('App:Config', 'c');           

        return $qb->getQuery()->getOneOrNullResult();
    }
}