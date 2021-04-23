<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Title;

class DatabaseService
{
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function databaseUrl(string $url, int $accessRestrictionId, string $proxyPrefix = ''): string
    {
        global $proxyURL;

        if (1 == $accessRestrictionId) { // Public resources don't need a proxy
            return $url;
        }

        return $proxyPrefix ? $proxyPrefix.$url : $proxyURL.$url;
    }

    public function newestDatabases()
    {
        return array_map([$this, 'arrangeDatabaseForDisplay'],
                         $this->em->getRepository(Title::class)->newPublicDatabases());
    }


    public function arrangeDatabaseForDisplay($database): array
    {
        return [
            'title' => $database['title'],
            'url' => $this->databaseUrl($database['location'], $database['restrictionsId']),
        ];
    }
}
