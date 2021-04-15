<?php

namespace App\Repository;

use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\GuideService;

/**
 * @method Subject|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subject|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subject[]    findAll()
 * @method Subject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subject::class);
    }

    public function newPublicGuides(GuideService $guideService, $numToFetch = 5)
    {
        // TODO: narrow by guides that are of a public type
        return $this->createQueryBuilder('s')
        ->select(['s.shortform', 's.subject'])
        ->andWhere('s.active = :val')
        ->setParameter('val', true)
        ->orderBy('s.subjectId', 'DESC')
        ->setMaxResults($numToFetch)
        ->getQuery()
        ->getResult()
        ;
    }
}
