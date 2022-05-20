<?php

namespace App\Repository;

use App\Entity\Title;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\Regexp;
use Symfony\Component\Finder\Expression\Regex;
use Symfony\Contracts\Cache\CacheInterface;

class DatabaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, CacheInterface $cache)
    {
        parent::__construct($registry, Title::class);

        $this->_qb = $this->createQueryBuilder('t', 't.titleId');
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
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
}