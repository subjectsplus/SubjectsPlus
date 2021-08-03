<?php

namespace App\Repository;

use App\Entity\Chchchanges;
use App\Entity\Faq;
use App\Entity\Staff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;

/**
 * @method Chchchanges|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chchchanges|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chchchanges[]    findAll()
 * @method Chchchanges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChchchangesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chchchanges::class);
    }

    public function getStaffByFaq(Faq $faq) {
        return $this->createQueryBuilder('ch')
        ->select('s as staff, ch.dateAdded as dateAdded')
        ->innerJoin('\App\Entity\Staff', 's', 'WITH', 's = (ch.staff)')
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq('ch.ourtable', 'faq')))
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq('ch.recordId', $faq->getFaqId())))
        ->orderBy('dateAdded', 'DESC')
        ->getQuery()
        ->getResult();
    }

    public function getFaqsByStaff(Staff $staff) {
        return $this->createQueryBuilder('ch')
        ->select('f as faq, ch.dateAdded as dateAdded, ch.message as message')
        ->innerJoin('\App\Entity\Faq', 'f', 'WITH', 'f.faqId = ch.recordId')
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq('ch.ourtable', 'faq')))
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq('ch.staff', $staff)))
        ->orderBy('dateAdded', 'DESC')
        ->getQuery()
        ->getResult();
    }
}