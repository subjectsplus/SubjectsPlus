<?php

namespace App\Service;

use App\Repository\SubjectRepository;

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

    public function getSubjectTabs($subject_id)
    {
        
    }

}