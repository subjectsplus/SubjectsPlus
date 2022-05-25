<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MediaService;
use App\Entity\Media;
use App\Entity\MediaAttachment;
use App\Entity\Staff;
use App\Enum\ImageSizeType;

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

    /**
     * Mime Type Icons Config
     * 
     * @var array $mimeTypeIcons
     */
    private $mimeTypeIcons;

    /**
     * FontAwesome icon size class
     * 
     * @var string $fontSizeIconClass
     */
    private $iconsFontSizeClass;

    public function __construct(MediaService $mediaService, EntityManagerInterface $entityManager, 
        array $mimeTypeIcons, string $iconsFontSizeClass)
    {
        $this->mediaService = $mediaService;
        $this->entityManager = $entityManager;
        $this->mimeTypeIcons = $mimeTypeIcons;
        $this->iconsFontSizeClass = $iconsFontSizeClass;
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('get_media_url', [$this, 'getMediaUrl']),
            new TwigFilter('get_media_by_staff', [$this, 'getMediaByStaff']),
            new TwigFilter('get_media_attachments', [$this, 'getMediaAttachments']),
            new TwigFilter('get_mime_type_icon_class', [$this, 'getMimeTypeIconClass']),
        ];
    }

    public function getMediaUrl(Media $media, string $size = 'original')
    {
        $sizeType = ImageSizeType::ORIGINAL_IMAGE;

        if ($size === 'large') {
            $sizeType = ImageSizeType::LARGE_IMAGE;
        } else if ($size === 'medium') {
            $sizeType = ImageSizeType::MEDIUM_IMAGE;
        } else if ($size === 'small') {
            $sizeType = ImageSizeType::SMALL_IMAGE;
        }
        
        return $this->mediaService->getRelativeUrlFromMedia($media, $sizeType);
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

    public function getMimeTypeIconClass(Media $media)
    {
        $mimeType = $media->getMimeType();
        //dd('mime type icon: ', $this->mimeTypeIcons[$mimeType]);
        
        if (\array_key_exists($mimeType, $this->mimeTypeIcons)) {
            return $this->mimeTypeIcons[$mimeType] . ' ' . $this->iconsFontSizeClass;
        } else if (\strlen($mimeType) > 6 && \array_key_exists(substr($mimeType, 0, 6), $this->mimeTypeIcons)) {
            return $this->mimeTypeIcons[substr($mimeType, 0, 6)] . ' ' . $this->iconsFontSizeClass;
        } else {
            return $this->mimeTypeIcons['generic'] . ' ' . $this->iconsFontSizeClass;
        }
    }
}