<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service\MediaService;
use App\Entity\Media;

class MediaExtension extends AbstractExtension
{
    private $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('get_media_url', [$this, 'getMediaUrl']),
        ];
    }

    public function getMediaUrl(Media $media)
    {
        return $this->mediaService->getRelativeUrlFromMedia($media);
    }

}