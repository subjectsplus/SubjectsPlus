<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service\EmbedService;

class EmbedExtension extends AbstractExtension
{
    /**
     * EmbedService
     *
     * @var EmbedService $embedService
     */
    private $embedService;

    public function __construct(EmbedService $embedService)
    {
        $this->embedService = $embedService;
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('get_embed_link', [$this, 'getEmbedLink'])
        ];
    }

    public function getEmbedLink(string $source, string $type): string {
        return $this->embedService->getEmbedLink($source, $type);
    }
}