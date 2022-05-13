<?php

namespace App\Controller\Frontend;

use App\Controller\Frontend\FrontendBaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DatabaseService;
use App\Service\ThemeService;

class DatabaseController extends FrontendBaseController
{
    /**
     * @var DatabaseService
     */
    private $databaseService;

    /**
     * @var ThemeService
     */
    private $themeService;


    public function __construct(DatabaseService $databaseService, ThemeService $themeService)
    {
        parent::__construct($themeService);
        $this->databaseService = $databaseService;
    }

    /**
     * @Route("/databases", name="frontend_database")
     */
    public function index(): Response
    {
        return $this->render('database/index.html.twig', [
            'controller_name' => 'DatabaseController',
            //'letters' => getLetters('databases'),
            'newestDatabases' => $this->databaseService->newestDatabases(),
            'trialDatabases' => $this->databaseService->getTrialDatabases(),

        ]);
    }
}
