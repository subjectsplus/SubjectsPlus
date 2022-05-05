<?php

namespace App\Repository;

use App\Entity\ConfigCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConfigCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigCategory[]    findAll()
 * @method ConfigCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigCategory::class);
    }

    // /**
    //  * @return ConfigCategory[] Returns an array of ConfigCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConfigCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
