<?php

namespace App\Repository;

use App\Entity\MediaAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaAttachment[]    findAll()
 * @method MediaAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaAttachment::class);
    }
}
