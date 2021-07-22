<?php

namespace App\Repository;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\Faqpage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Faq|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faq|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faq[]    findAll()
 * @method Faq[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faq::class);
    }

    public function getAllFaqs()
    {
        return $this->baseQuery()
        ->getQuery()
        ->getResult();
    }

    public function getFaqsBySubject(Subject $subject)
    {
        $query = $this->baseQuery();
        $query->innerJoin('f.faqSubject', 'fs');
        $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq("fs.subject", $subject)));
        return $query->getQuery()->getResult();
    }

    public function getFaqsByCollection(Faqpage $faqPage)
    {
        $query = $this->baseQuery();
        $query->innerJoin('f.faqFaqpage', 'ffp');
        $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq("ffp.faqpage", $faqPage)));
        return $query->getQuery()->getResult();
    }

    private function baseQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('f', 'f.faqId')
        ->select('f');
    }

}