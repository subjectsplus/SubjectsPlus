<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DatabaseService;
use App\Service\ThemeService;

class DatabaseController extends AbstractController
{
    /**
     * @var DatabaseService
     */
    private $databaseService;

    /**
     * @var ThemeService
     */
    private $themeService;

    public function __construct(DatabaseService $databaseService,
        ThemeService $themeService)
    {
        $this->databaseService = $databaseService;
        $this->themeService = $themeService;
    }

    /**
     * @Route("/databases", name="frontend_database")
     */
    public function index(): Response
    {
        return $this->render($this->themeService->getThemePath('frontend/database/index.html.twig'), [
            'controller_name' => 'DatabaseController',
            //'letters' => getLetters('databases'),
            'newestDatabases' => $this->databaseService->newestDatabases(),
            'trialDatabases' => $this->databaseService->getTrialDatabases(),

        ]);
    }
}
