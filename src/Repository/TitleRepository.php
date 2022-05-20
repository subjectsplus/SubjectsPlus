<?php

namespace App\Repository;

use App\Entity\Title;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;

/**
 * @method Title|null find($id, $lockMode = null, $lockVersion = null)
 * @method Title|null findOneBy(array $criteria, array $orderBy = null)
 * @method Title[]    findAll()
 * @method Title[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TitleRepository extends ServiceEntityRepository
{
    private $basic_fields = ['t.title', 'l.location', 'r.restrictionsId', 'f.format'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Title::class);
    }

    public function newestPublicDatabases($numToFetch = 5)
    {
        return $this->baseQuery()
            ->select($this->basic_fields)
            ->where("l.eresDisplay = 'Y'")
            ->orderBy('t.titleId', 'DESC')
            ->setMaxResults($numToFetch)
            ->getQuery()
            ->useQueryCache(true)
            ->enableResultCache(15)
            ->getResult()
        ;
    }

    public function getTrialDatabases($numToFetch = 5)
    {
        return $this->baseQuery()
            ->select($this->basic_fields)
            ->where("l.eresDisplay = 'Y'")
            ->andWhere("l.recordStatus = 'Trial'")
            ->orderBy('t.titleId', 'DESC')
            ->setMaxResults($numToFetch)
            ->getQuery()
            ->useQueryCache(true)
            ->enableResultCache(15)
            ->getResult()
        ;
    }

    public function searchBySubstring(string $substring, $numToFetch = 10, $patronView = true)
    {
        $query = $this->baseQuery()
        ->select($this->basic_fields)
        ->where('t.title LIKE :substring')
        ->setParameter('substring', "%$substring%")
        ->orderBy('t.title', 'ASC')
        ->setMaxResults($numToFetch);

        if ($patronView)
        {
            $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq('l.eresDisplay', 'Y')));
        }
        return $query->getQuery()->getResult();
    }

    public function getDatabasesBy(bool $patronView = true, array $criteria = [], int $num_to_fetch = null)
    {
        $query = $this->baseQuery()
                      ->select('t, l, r, f')
                      ->orderBy('t.title', 'ASC');
        if ($patronView)
        {
            $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq('l.eresDisplay', 'Y')));
        }
        if (array_key_exists('letter', $criteria))
        {
            $query->addCriteria(Criteria::create()->where(Criteria::expr()->startsWith('t.title', $criteria['letter'])));
        }
        elseif (array_key_exists('startsWithNumber', $criteria))
        {
            $query->andWhere('REGEXP(t.title, :startswithnum) = true')
                  ->setParameter('startswithnum', '^[[:digit:]]');
        }
        if (array_key_exists('noAccessRestrictions', $criteria))
        {
            $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq('r.restrictionsId', 1)));
        }
        if (array_key_exists('subjectId', $criteria))
        {
            $query->innerJoin('t.ranks', 'ranks')
                  ->innerJoin('ranks.subject', 's')
                  ->addCriteria(Criteria::create()->where(Criteria::expr()->eq('s.subjectId', $criteria['subjectId'])));
        }
        if (array_key_exists('type', $criteria))
        {
            $query->addCriteria(Criteria::create()->where(Criteria::expr()->contains('l.ctags', $criteria['type'])));
        }
        if (array_key_exists('format', $criteria))
        {
            $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq('f.format', $criteria['format'])));
        }

        if ($num_to_fetch !== null && $num_to_fetch > 0) {
            $query->setMaxResults($num_to_fetch);
        }

        return $query->getQuery()->getResult();
    }

    private function baseQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('t')
        ->innerJoin('t.location', 'l')
        ->innerJoin('l.accessRestrictions', 'r')
        ->innerJoin('l.format', 'f');
    }

}
