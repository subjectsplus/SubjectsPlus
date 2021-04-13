<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GuideService;
use App\Repository\SubjectRepository;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", priority=10)
     * @Route("/index.php", name="index.php", priority=10)
     * @Route("/subjects/", name="subjects", priority=10)
     * @Route("/subjects/index.php", name="subjects/index.php", priority=10)
     */
    public function index(GuideService $guideService, SubjectRepository $subjectRepository): Response
    {
        return $this->render('public/index.html.twig', [
            'guideTypes' => $this->guideTypes($guideService),
            'newestGuides' => $this->newestGuides($guideService, $subjectRepository)
        ]);
    }

    private function guideTypes(GuideService $guideService): array
    {
        global $guide_types;
        return array_filter($guide_types, array($guideService, 'guideTypeIsVisible'));
    }

    // Returns array of [shortform: label]
    private function newestGuides($guideService, $subjectRepository): array
    {
        return $subjectRepository->newPublicGuides($guideService);
        return [
            ['shortform' => 'reserves', 'label' => 'Course Reserves']
        ];
    }
}
