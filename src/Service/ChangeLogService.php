<?php

namespace App\Service;

use App\Entity\Staff;
use App\Entity\Chchchanges;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\UtilityService;

class ChangeLogService {

    private $entityManager;
    const MAXIMUM_CHAR_LENGTH = 255;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addLog(Staff $staff, string $ourtable, int $recordId, ?string $recordTitle, ?string $message) {
        // Make sure the columns are non-html and meet character limit requirements
        if ($recordTitle !== null) {
            $recordTitle = UtilityService::cleanString($recordTitle);
            if (strlen($recordTitle) > self::MAXIMUM_CHAR_LENGTH) {
                $recordTitle = substr($recordTitle, 0, self::MAXIMUM_CHAR_LENGTH);
            }
        }

        if ($message !== null) {
            $message = UtilityService::cleanString($message);
            if (strlen($message) > self::MAXIMUM_CHAR_LENGTH) {
                $message = substr($message, 0, self::MAXIMUM_CHAR_LENGTH);
            }
        }

        $chchChanges = new Chchchanges();
        $chchChanges->setStaff($staff);
        $chchChanges->setOurtable($ourtable);
        $chchChanges->setRecordId($recordId);
        $chchChanges->setRecordTitle($recordTitle);
        $chchChanges->setMessage($message);

        $this->entityManager->persist($chchChanges);
        $this->entityManager->flush();
    }
}