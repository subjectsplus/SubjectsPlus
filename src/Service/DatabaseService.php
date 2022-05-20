<?php

namespace App\Service;

use App\Repository\DatabaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Title;
use Symfony\Contracts\Cache\CacheInterface;

class DatabaseService
{
    public function __construct(EntityManagerInterface $em, RequestStack $requestStack, DatabaseRepository $databaseRepository, CacheInterface $cache) {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->_dbRepo = $databaseRepository;
        $this->_cache = $cache;
    }

    public function databaseUrl(string $url, int $accessRestrictionId, string $proxyPrefix = ''): string
    {
        //TODO: add proxyUrl to config
        global $proxyURL;

        if (1 == $accessRestrictionId) { // Public resources don't need a proxy
            return $url;
        }

        return $proxyPrefix ? $proxyPrefix.$url : $proxyURL.$url;
    }

    public function getAlphaLetters()
    {
        return $this->_dbRepo->getLetters();
    }

    public function testCache()
    {
        $letter = 'A';
        $result = $this->_cache->get('letter_'.md5($letter), function() use($letter) {
            return $letter;
        });

        return $result;
    }
    public function getTrialDatabases()
    {
        return array_map([$this, 'arrangeDatabaseForDisplay'],
                         $this->em->getRepository(Title::class)->getTrialDatabases());
    }


    public function newestDatabases()
    {
        return array_map([$this, 'arrangeDatabaseForDisplay'],
                         $this->em->getRepository(Title::class)->newestPublicDatabases());
    }


    public function arrangeDatabaseForDisplay($database): array
    {
        return [
            'title' => $database['title'],
            'url' => $this->databaseUrl($database['location'], $database['restrictionsId']),
        ];
    }

    public function searchCriteriaFromParams(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $criteria = [];

        if ($request->query->has('letter'))
        {
            $letter = $request->query->getAlpha('letter');
            if ($letter == 'Num')
            {
                $criteria['startsWithNumber'] = true;
            }
            elseif (in_array($letter, ['Free', 'Unrestricted']))
            {
                $criteria['noAccessRestrictions'] = true;
            }
            elseif ($this->isLetterValid($letter))
            {
                $criteria['letter'] = $letter;
            }
        }

        if ($request->query->has('subject_id'))
        {
            $criteria['subjectId'] = $request->query->getDigits('subject_id');
        }

        if ($request->query->has('type'))
        {
            $criteria['type'] = $request->query->getAlpha('type');
        }

        if (empty($criteria) && !$this->userWantsAllDatabases())
        {
            // Default case
            $criteria['letter'] = 'A';
        }

        return $criteria;

    }

    private function isLetterValid(string $letter): bool
    {
        return (strlen($letter) == 1);
    }

    private function userWantsAllDatabases(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        return $request->query->has('letter') && ($request->query->get('letter') == 'All');
    }
}
