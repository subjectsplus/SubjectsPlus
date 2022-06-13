<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service\PlusletCompatibilityService;
use App\Entity\Pluslet;

class PlusletCompatibilityExtension extends AbstractExtension
{
    /**
     * PlusletCompatibilityService
     *
     * @var PlusletCompatibilityService $tokenService
     */
    private $plusletCompatibilityService;

    public function __construct(PlusletCompatibilityService $plusletCompatibilityService)
    {
        $this->plusletCompatibilityService = $plusletCompatibilityService;
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('convert_subject_specialist_extra_field', [$this, 'convertSubjectSpecialistExtraField'])
        ];
    }

    public function convertSubjectSpecialistExtraField(Pluslet $pluslet) {
        //return $this->plusletCompatibilityService->convertSubjectSpecialist($pluslet->getPlusletId());
    }
}