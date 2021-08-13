<?php

namespace App\Repository;

use App\Entity\Faq;
use App\Entity\FaqSubject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;

/**
 * @method FaqSubject|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqSubject|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqSubject[]    findAll()
 * @method FaqSubject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqSubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaqSubject::class);
    }

    public function getSubjectsByFaq(Faq $faq) {
        return $this->createQueryBuilder('fs')
        ->select('s')
        ->innerJoin('\App\Entity\Subject', 's', 'WITH', 's = fs.subject')
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq("fs.faq", $faq)))
        ->getQuery()
        ->getResult();
    }
}