<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Service\PlusletService;

class PlusletExtension extends AbstractExtension
{
    /**
     * PlusletService
     *
     * @var PlusletService $plusletService
     */
    private $plusletService;

    public function __construct(PlusletService $plusletService)
    {
        $this->plusletService = $plusletService;
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('get_pluslet', [$this, 'getPluslet'])
        ];
    }

    public function getPluslet(int $plusletId) {
        return $this->plusletService->getPluslet($plusletId);
    }
}