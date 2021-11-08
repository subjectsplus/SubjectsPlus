<?php

namespace App\Service;

use App\Entity\Staff;
use App\Entity\Chchchanges;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ChangeLogService {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addLog(Staff $staff, $ourtable, $recordId, $recordTitle, $message) {
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