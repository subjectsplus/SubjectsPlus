<?php

namespace App\Repository;

use App\Entity\Subject;
use App\Service\GuideService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subject|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subject|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subject[]    findAll()
 * @method Subject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectRepository extends ServiceEntityRepository
{

    private $basic_fields = ['s.shortform', 's.subject'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subject::class);
    }

    public function getGuideListForStaff()
    {
        return $this->createQueryBuilder('s')
        ->select($this->basic_fields)
        ->orderBy('s.subject', 'ASC')
        ->getQuery()
        ->getArrayResult();
    }

    public function guidesByType()
    {
        return $this->createQueryBuilder('s')
        ->select(['s.type', 's.shortform', 's.subject'])
        ->where('s.active = true')
        ->addgroupBy('s.type')
        ->addgroupBy('s.shortform')
        ->addgroupBy('s.subject')
        ->orderBy('s.subject', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function newPublicGuides(GuideService $guideService, $numToFetch = 5)
    {
        // TODO: narrow by guides that are of a public type
        return $this->createQueryBuilder('s')
        ->select($this->basic_fields)
        ->andWhere('s.active = true')
        ->orderBy('s.subjectId', 'DESC')
        ->setMaxResults($numToFetch)
        ->getQuery()
        ->getResult()
        ;
    }

    public function searchBySubstring(string $substring, $numToFetch = 10)
    {
        // TODO: narrow by guides that are of a public type
        return $this->createQueryBuilder('s')
        ->select($this->basic_fields)
        ->where('s.active = true')
        ->andWhere('s.subject LIKE :substring')
        ->setParameter('substring', "%$substring%")
        ->orderBy('s.subjectId', 'DESC')
        ->setMaxResults($numToFetch)
        ->getQuery()
        ->getResult()
        ;
    }
}
