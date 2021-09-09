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

    public function getAllFaqsBySubject($active=null)
    {
        $results = array();
        
        $subjects = $this->createQueryBuilder('f')
        ->select('(fs.subject) AS subject')
        ->innerJoin('f.faqSubject', 'fs')
        ->groupBy('subject')
        ->getQuery()
        ->getResult();


        foreach($subjects as $subject) {
            $subjectId = $subject["subject"];
            $subject = $this->getEntityManager()->find(Subject::class, $subjectId);
            $name = $subject->getSubject();
            $results[$name] = $this->getFaqsBySubject($subject, $active);
        }

        return $results;
    }

    public function getFaqsBySubject(Subject $subject, $active=null)
    {
        $query = $this->baseQuery();
        $query->innerJoin('f.faqSubject', 'fs');
        $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq("fs.subject", $subject)));
        if ($active !== null && ($active == 0 || $active == 1))
            $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq("f.active", $active)));

        return $query->getQuery()->getResult();
    }

    public function getAllFaqsByCollection($active=null)
    {
        $results = array();

        $collections = $this->createQueryBuilder('f')
        ->select('(ffp.faqpage) AS faqpage')
        ->innerJoin('f.faqFaqpage', 'ffp')
        ->groupBy('faqpage')
        ->getQuery()
        ->getResult();

        foreach($collections as $collection) {
            $faqPageId = $collection["faqpage"];
            $faqPage = $this->getEntityManager()->find(Faqpage::class, $faqPageId);
            $name = $faqPage->getName();
            $results[$name] = $this->getFaqsByCollection($faqPage, $active);
        }

        return $results;
    }

    public function getFaqsByCollection(Faqpage $faqPage, $active=null)
    {
        $query = $this->baseQuery();
        $query->innerJoin('f.faqFaqpage', 'ffp');
        $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq("ffp.faqpage", $faqPage)));
        if ($active !== null && ($active == 0 || $active == 1))
            $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq("f.active", $active)));

        return $query->getQuery()->getResult();
    }

    private function baseQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('f', 'f.faqId')
        ->select('f');
    }

}