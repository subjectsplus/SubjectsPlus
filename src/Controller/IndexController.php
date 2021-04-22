<?php

namespace App\Controller;

use App\Entity\Title;
use App\Repository\SubjectRepository;
use App\Service\DatabaseService;
use App\Service\GuideService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @Route("/", name="index", priority=10)
     * @Route("/index.php", name="index.php", priority=10)
     * @Route("/subjects/", name="subjects", priority=10)
     * @Route("/subjects/index.php", name="subjects/index.php", priority=10)
     */
    public function index(GuideService $guideService, SubjectRepository $subjectRepository): Response
    {
        return $this->render('public/index.html.twig', [
            'collections' => $this->collections(),
            'guides' => $this->guidesByType(),
            'guideTypes' => $this->guideTypes($guideService),
            'newestDatabases' => $this->newestDatabases(),
            'newestGuides' => $this->newestGuides($guideService, $subjectRepository),
        ]);
    }

    private function collections(): array
    {
        return array_map([$this, 'arrangeCollectionForDisplay'],
                         $this->getDoctrine()
                              ->getRepository(\App\Entity\Collection::class)
                              ->findAll());
    }

    private function guideTypes(GuideService $guideService): array
    {
        global $guide_types;

        return array_filter($guide_types, [$guideService, 'guideTypeIsVisible']);
    }

    private function guidesByType(): array
    {
        return $this->getDoctrine()->getRepository(\App\Entity\Subject::class)->guidesByType();
    }

    private function newestDatabases()
    {
        //return $this->getDoctrine()->getRepository(Title::class)->newPublicDatabases();
        $manager = $this->getDoctrine()->getManager();

        return array_map([$this, 'arrangeDatabaseForDisplay'], $manager->getRepository(Title::class)->newPublicDatabases());
    }

    // Returns array of [shortform: label]
    private function newestGuides($guideService, $subjectRepository): array
    {
        return $subjectRepository->newPublicGuides($guideService);
    }

    private function arrangeCollectionForDisplay($collection): array
    {
        return [
            'title' => $collection->getTitle(),
            'shortform' => $collection->getShortForm()
        ];

    }

    private function arrangeDatabaseForDisplay($database): array
    {
        return [
            'title' => $database['title'],
            'url' => $this->databaseService->databaseUrl($database['location'], $database['restrictionsId']),
        ];
    }
}
