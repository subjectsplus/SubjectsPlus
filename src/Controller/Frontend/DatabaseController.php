<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DatabaseService;

class DatabaseController extends AbstractController
{
    private $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @Route("/databases", name="frontend_database")
     */
    public function index(): Response
    {
        return $this->render('frontend/database/index.html.twig', [
            'controller_name' => 'DatabaseController',
            //'letters' => getLetters('databases'),
            'newestDatabases' => $this->databaseService->newestDatabases(),
            'trialDatabases' => $this->databaseService->getTrialDatabases(),

        ]);
    }
}
