<?php

namespace App\Repository;

use App\Entity\Record;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\Regexp;
use Symfony\Component\Finder\Expression\Regex;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @method Record|null find($id, $lockMode = null, $lockVersion = null)
 * @method Record|null findOneBy(array $criteria, array $orderBy = null)
 * @method Record[]    findAll()
 * @method Record[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);

        $this->_qb = $this->createQueryBuilder('t', 't.titleId');
    }

    /**
     * @return float|int|mixed|string
     */
    public function getLetters()
    {
        return $this->_qb->select(
        $this->_qb->expr()->substring('t.title', 1,1) . 'AS initial')
             ->distinct(true)
             ->andWhere('REGEXP('. $this->_qb->expr()->substring('t.title', 1,1) .', :regexp) = true')
             ->setParameter('regexp', '[A-Z]')
             ->orderBy('initial', 'ASC')
             ->getQuery()
             ->useQueryCache(true)
             ->enableResultCache(15)
             ->getResult();
    }

    // /**
    //  * @return Record[] Returns an array of Record objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Record
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
