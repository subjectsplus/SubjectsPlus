<?php

namespace App\Service;

use App\Repository\SubjectRepository;
use App\Entity\Subject;

class SubjectService
{
    /**
     * @var SubjectRepository
     */
    private $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * @return \App\Entity\Subject[]
     */
    public function getSubjectIndex()
    {
        return $this->subjectRepository->findAllSubjectsAlphabetical();
    }

    public function getSubjectByShortForm($shortform)
    {
        return $this->subjectRepository->findSubjectByShortForm($shortform);
    }

    public function processSubjectStaff(Subject $subject, $staffMembers) {
        $currentStaff = $subject->getStaff()->toArray();
        $staffAdded = array_diff($staffMembers, $currentStaff); // Newly added staff
        $staffRemoved = array_diff($currentStaff, $staffMembers); // Staff removed

        // Add new Staff to Subject
        if (!empty($staffAdded))
            $this->addStaffMembersToSubject($subject, $staffAdded);

        // Delete old staff from Subject
        if (!empty($staffRemoved))
            $this->removeStaffMembersFromSubject($subject, $staffRemoved);
    }

    public function addStaffMembersToSubject(Subject $subject, $staffMembers) {
        foreach($staffMembers as $staffMember) {
            $subject->addStaff($staffMember);
        }
    }

    public function removeStaffMembersFromSubject(Subject $subject, $staffMembers) {
        foreach($staffMembers as $staffMember) {
            $subject->removeStaff($staffMember);
        }
    }

}