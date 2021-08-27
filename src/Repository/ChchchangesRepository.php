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

    private $staffFields = ['s.staffId', 's.lname', 's.fname', 's.title', 's.email'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chchchanges::class);
    }

    public function getStaffByFaq(Faq $faq) {
        return $this->createQueryBuilder('ch')
        ->select($this->staffFields)
        ->addSelect('ch.dateAdded')
        ->innerJoin('\App\Entity\Staff', 's', 'WITH', 's = (ch.staff)')
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq('ch.ourtable', 'faq')))
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq('ch.recordId', $faq->getFaqId())))
        ->orderBy('ch.dateAdded', 'DESC')
        ->getQuery()
        ->getArrayResult();
    }

    public function getFaqsByStaff(Staff $staff, $activeFaq=null) {
        $query = $this->createQueryBuilder('ch')
        ->select('f as faq, ch.dateAdded as dateAdded, ch.message as message')
        ->innerJoin('\App\Entity\Faq', 'f', 'WITH', 'f.faqId = ch.recordId')
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq('ch.ourtable', 'faq')))
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq('ch.staff', $staff)))
        ->addCriteria(Criteria::create()->where(Criteria::expr()->neq('ch.message', 'delete')))
        ->orderBy('dateAdded', 'DESC');

        if ($activeFaq !== null && ($activeFaq == 0 || $activeFaq == 1))
            $query->addCriteria(Criteria::create()->where(Criteria::expr()->eq('f.active', $activeFaq)));
            
        return $query->getQuery()->getArrayResult();
    }
}