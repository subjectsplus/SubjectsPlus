<?php

namespace App\Repository;

use App\Entity\Title;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Title|null find($id, $lockMode = null, $lockVersion = null)
 * @method Title|null findOneBy(array $criteria, array $orderBy = null)
 * @method Title[]    findAll()
 * @method Title[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Title::class);
    }

    public function newPublicDatabases($numToFetch = 5)
    {
        return $this->createQueryBuilder('t')
        ->select(['t.title', 'l.location', 'r.restrictionsId'])
        ->innerJoin('t.location', 'l')
        ->innerJoin('l.accessRestrictions', 'r')
        ->where("l.eresDisplay = 'Y'")
        ->orderBy('t.titleId', 'DESC')
        ->setMaxResults($numToFetch)
        ->getQuery()
        ->getResult()
        ;
    }
}
