<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pluslet;

class PlusletCompatibilityService {

    private $entityManager;
    private $plusletRepository;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->plusletRepository = $entityManager->getRepository(Pluslet::class);
    }

    // public function convertSubjectSpecialist(int $plusletId) {
    //     /** @var Pluslet $pluslet */
    //     $pluslet = $this->plusletRepository->findOneBy(['plusletId' => $plusletId]);
    //     if ($pluslet->getType() === 'SubjectSpecialist') {
    //         $extra = \json_decode($pluslet->getExtra());
    //         dd($extra);
    //         // Check if pluslet is using the old format
    //         if (\gettype($extra) === 'object') {
    //             $newExtra = [];
    //             $staffIds = [];

    //             // 
    //             foreach ($extra as $key => $value) {
    //                 // split key into field and staffId
    //                 // Ex: showName401
    //                 preg_match("/([a-zA-Z]+)(\\d+)/", $key, $matches);
    //                 $field = $matches[0];
    //                 $staffId = $matches[1];
    //                 array_push($newExtra, [
    //                     "staffId" => $staffId,
    //                     $field => $value[0]
    //                 ] 
    //                 })
    //             }
    //         }
    //     }
        /*
            {"showName403": ['Yes'], "showEmail403": ['Yes'], "showName800": ['Yes]}
        */
        /*
            New Format: 
                [
                    {
                        staffId: [number],
                        showName: [boolean],
                        showPhoto: [boolean],
                        showTitle: [boolean],
                        showEmail: [boolean],
                        showPhone: [boolean]
                    },
                    {
                        staffId: [number],
                        showName: [boolean],
                        showPhoto: [boolean],
                        showTitle: [boolean],
                        showEmail: [boolean],
                        showPhone: [boolean]
                    }
                ]
        */
    //}
}