<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service\LazyLoadingService;

class LazyLoadingExtension extends AbstractExtension
{
    /**
     * LazyLoadingService
     *
     * @var LazyLoadingService $lazyLoadingService
     */
    private $lazyLoadingService;

    public function __construct(LazyLoadingService $lazyLoadingService)
    {
        $this->lazyLoadingService = $lazyLoadingService;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('lazy_loading', [$this, 'lazyLoading'])
        ];
    }

    public function lazyLoading(string $html) {
        return $this->lazyLoadingService->addLazyAttribute($html);
    }
}