<?php

namespace App\Service;

use App\Entity\Record;
use App\Repository\RecordRepository;

class RecordService
{

    private $_configService;

    private $_recordRepo;

    public function __construct(
        ConfigService $configService,
        RecordRepository $recordRepository
    ) {
        $this->_configService = $configService;
        $this->_recordRepo = $recordRepository;
    }


    public function getLetters()
    {
        return $this->_recordRepo->getLetters();
    }

    public function databaseUrl(string $url, int $accessRestrictionId, string $proxyPrefix = ''): string
    {
        $proxyURL = $this->_configService->getConfigValueByKey('proxy_url');

        if (1 == $accessRestrictionId) { // Public resources don't need a proxy
            return $url;
        }

        return $proxyPrefix ? $proxyPrefix.$url : $proxyURL.$url;
    }

    public function getDbsByEresDisplay($eresDisplay)
    {
        return $this->_recordRepo->getDbsByEresDisplay($eresDisplay);
    }

//    public function getDatabasesByLetter($letter) {
//
//        return array_map([$this, 'arrangeDatabaseForDisplay'],
//            $this->em->getRepository(Title::class)->getDatabasesBy(true, array('letter' => $letter)));
//
//    }
//
//    public function getTrialDatabases()
//    {
//        return array_map([$this, 'arrangeDatabaseForDisplay'],
//            $this->em->getRepository(Title::class)->getTrialDatabases());
//    }
//
//
//    public function newestDatabases()
//    {
//        return array_map([$this, 'arrangeDatabaseForDisplay'],
//            $this->em->getRepository(Title::class)->newestPublicDatabases());
//    }
//
//
//    public function arrangeDatabaseForDisplay($database): array
//    {
//        if(is_object($database)) {
//
//            //dd($database);
//            $values = $database->getLocation()->getValues();
//            $location = $values[0]->getLocation();
//            $restrictionId = $values[0]->getAccessRestrictions()->getRestrictionsId();
//
//            return [
//                'title' => $database->getTitle(),
//                'url' => $this->databaseUrl($location, $restrictionId),
//                'description' => $database->getDescription(),
//            ];
//        } elseif (is_array($database)) {
//            //dd($database);
//            return [
//                'title' => $database['title'],
//                'url' => $this->databaseUrl($database['location'], $database['restrictionsId']),
//                'description' => $database['description']
//            ];
//        }
//
//    }
//
//    public function searchCriteriaFromParams(): array
//    {
//        $request = $this->requestStack->getCurrentRequest();
//        $criteria = [];
//
//        if ($request->query->has('letter'))
//        {
//            $letter = $request->query->getAlpha('letter');
//            if ($letter == 'Num')
//            {
//                $criteria['startsWithNumber'] = true;
//            }
//            elseif (in_array($letter, ['Free', 'Unrestricted']))
//            {
//                $criteria['noAccessRestrictions'] = true;
//            }
//            elseif ($this->isLetterValid($letter))
//            {
//                $criteria['letter'] = $letter;
//            }
//        }
//
//        if ($request->query->has('subject_id'))
//        {
//            $criteria['subjectId'] = $request->query->getDigits('subject_id');
//        }
//
//        if ($request->query->has('type'))
//        {
//            $criteria['type'] = $request->query->getAlpha('type');
//        }
//
//        if (empty($criteria) && !$this->userWantsAllDatabases())
//        {
//            // Default case
//            $criteria['letter'] = 'A';
//        }
//
//        return $criteria;
//
//    }
//
//    private function isLetterValid(string $letter): bool
//    {
//        return (strlen($letter) == 1);
//    }
//
//    private function userWantsAllDatabases(): bool
//    {
//        $request = $this->requestStack->getCurrentRequest();
//        return $request->query->has('letter') && ($request->query->get('letter') == 'All');
//    }
}