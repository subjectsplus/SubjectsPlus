<?php

namespace App\Repository;



use App\Entity\Faq;
use App\Entity\Faqpage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Faqpage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faqpage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faqpage[]    findAll()
 * @method Faqpage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqpageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faqpage::class);
    }

    private function baseQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('fp', 'fp.faqpageId')
                    ->select('fp');
    }


    public function getAllFaqCollectionsAlpha()
    {
        return $this->baseQuery('fp')
                    ->orderBy('fp.name', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
}