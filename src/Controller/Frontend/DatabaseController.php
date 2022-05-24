<?php

namespace App\Controller\Frontend;

use App\Controller\Frontend\FrontendBaseController;
use App\Service\ConfigService;
use Symfony\Component\BrowserKit\Request;
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
    private $_databaseService;

    /**
     * @var ConfigService
     */
    private $_configService;

    /**
     * @var ThemeService
     */
    private $themeService;


    public function __construct(
        DatabaseService $databaseService,
        ThemeService $themeService,
        ConfigService $configService)
    {
        parent::__construct($themeService);
        $this->_databaseService = $databaseService;
        $this->_configService   = $configService;
    }

    /**
     * @Route("/", name="frontend_database")
     * @Route("/letter/{letter}", name="frontend_db_by_letter")
     */
    public function index($letter = 'A'): Response
    {

        return $this->render('database/index.html.twig', [
            'controller_name' => 'DatabaseController',
            'testCache' => $this->_databaseService->testCache(),
            'letters' => $this->_databaseService->getAlphaLetters(),
            'newestDatabases' => $this->_databaseService->newestDatabases(),
            'trialDatabases' => $this->_databaseService->getTrialDatabases(),
            'ctags' => $this->_configService->getConfigValueByKey('ctag'),
            'dbsByLetter' => $this->_databaseService->getDatabasesByLetter($letter)

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
