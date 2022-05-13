<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

class DatabaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass, EntityManager $entityManager)
    {
        parent::__construct($registry, $entityClass);

        $this->em = $entityManager;

//        $this->qb =
    }
}