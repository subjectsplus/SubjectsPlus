<?php

namespace App\Repository;

use App\Entity\Faq;
use App\Entity\FaqFaqpage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;

/**
 * @method FaqFaqpage|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqFaqpage|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqFaqpage[]    findAll()
 * @method FaqFaqpage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqFaqpageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaqFaqpage::class);
    }

    public function getFaqPagesByFaq(Faq $faq) {
        return $this->createQueryBuilder('ffp')
        ->select('fp')
        ->innerJoin('\App\Entity\Faqpage', 'fp', 'WITH', 'fp = ffp.faqpage')
        ->addCriteria(Criteria::create()->where(Criteria::expr()->eq("ffp.faq", $faq)))
        ->getQuery()
        ->getResult();
    }
}