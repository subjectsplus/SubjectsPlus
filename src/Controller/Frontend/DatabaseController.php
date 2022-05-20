<?php

namespace App\Controller\Frontend;

use App\Controller\Frontend\FrontendBaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DatabaseService;
use App\Service\ThemeService;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @Route ("/databases")
 */
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
     * @Route("/", name="frontend_database")
     */
    public function index(): Response
    {
        //dd($this->databaseService->getAlphaLetters());

        return $this->render('database/index.html.twig', [
            'controller_name' => 'DatabaseController',
            'testCache' => $this->databaseService->testCache(),
            'letters' => $this->databaseService->getAlphaLetters(),
            'newestDatabases' => $this->databaseService->newestDatabases(),
            'trialDatabases' => $this->databaseService->getTrialDatabases(),

        ]);
    }

    /**
     * @Route ("/test")
     * @return Response
     */
    public function test(): Response
    {

        $result = 'result: ';

        return $this->render('database/test.html.twig', [
            'result' => $result,
        ]);
    }
}
