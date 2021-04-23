<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Service\DatabaseService;

class Database extends AbstractExtension
{
    private $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('database_url', [$this, 'databaseUrl']),
        ];
    }

    public function databaseUrl(string $url, int $accessRestrictionId)
    {
        return $this->databaseService->databaseUrl($url, $accessRestrictionId);
    }

}