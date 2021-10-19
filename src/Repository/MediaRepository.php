<?php

namespace App\Repository;

use App\Entity\Media;
use App\Entity\Staff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    /**
     * @param Staff $staff The staff member
     * @param string $mediaType Type of media to query (all, image, generic)
     * @return Media[] Returns an array of Media objects from Staff
     */
    public function findByStaff(Staff $staff, string $mediaType = 'all')
    {
        $query = $this->createQueryBuilder('m')
        ->select('m');

        switch($mediaType) {
            case 'image':
                $query = $query->andWhere($query->expr()->eq(
                    $query->expr()->substring('m.mimeType', 1, 6),
                    $query->expr()->literal("image/")
                ));
                break;
            case 'generic':
                $query = $query->andWhere($query->expr()->neq(
                    $query->expr()->substring('m.mimeType', 1, 6),
                    $query->expr()->literal("image/")
                ));
                break;
        }

        $query = $query->andWhere('m.staff = :staff')
        ->setParameter('staff', $staff)
        ->orderBy('m.createdAt', 'DESC');

        return $query->getQuery()->getResult();
    }
}
