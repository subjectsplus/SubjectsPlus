<?php

namespace App\Controller\Patron;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DatabaseService;
use App\Entity\Title;

class DatabaseListController extends AbstractController
{
    private $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @Route("api/autocomplete/databases.json", name="databasesAutocomplete")
     */
    public function autocompleteDatabases(): JsonResponse
    {
        $request = Request::createFromGlobals();
        $query = $request->query->get('query') ? $request->query->get('query') : '';
        return new JsonResponse(array_map([$this->databaseService, 'arrangeDatabaseForDisplay'],
                                          $this->getDoctrine()
                                               ->getRepository(Title::class)
                                               ->searchBySubstring($query)));
    }


    /**
     * @Route("/subjects/databases.php", name="public_database_list")
     */
    public function index(): Response
    {
        return $this->render('patron/databases.html.twig', [
            'letters' => getLetters('databases'),
            'newestDatabases' => $this->databaseService->newestDatabases(),
            'trialDatabases' => $this->databaseService->getTrialDatabases(),
            'results' => $this->getDoctrine()
                              ->getRepository(Title::class)
                              ->getDatabasesBy(true, $this->databaseService->searchCriteriaFromParams())
                              ->getArrayResult(),
        ]);

    }


}
