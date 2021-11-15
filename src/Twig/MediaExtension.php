<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MediaService;
use App\Entity\Media;
use App\Entity\MediaAttachment;
use App\Entity\Staff;

class MediaExtension extends AbstractExtension
{
    /**
     * MediaService
     *
     * @var MediaService $mediaService
     */
    private $mediaService;

    /**
     * Entity Manager
     *
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    public function __construct(MediaService $mediaService, EntityManagerInterface $entityManager)
    {
        $this->mediaService = $mediaService;
        $this->entityManager = $entityManager;
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('get_media_url', [$this, 'getMediaUrl']),
            new TwigFilter('get_media_by_staff', [$this, 'getMediaByStaff']),
            new TwigFilter('get_media_attachments', [$this, 'getMediaAttachments']),
        ];
    }

    public function getMediaUrl(Media $media)
    {
        return $this->mediaService->getRelativeUrlFromMedia($media);
    }

    public function getMediaByStaff(Staff $staff, string $mediaType = 'all')
    {
        /**
         * MediaRepository
         * 
         * @var \App\Repository\MediaRepository $mediaRepo
         */
        $mediaRepo = $this->entityManager
        ->getRepository(Media::class);
        
        return $mediaRepo->findByStaff($staff, $mediaType);
    }

    public function getMediaAttachments(Media $media)
    {
        $mediaAttRepo = $this->entityManager
        ->getRepository(MediaAttachment::class);

        return $mediaAttRepo->findBy(['media' => $media]);
    }
}