<?php

namespace App\Controller\Staff;

use App\Entity\Log;
use App\Entity\Staff;
use App\Service\ChangeLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/logs")
 */
class LogController extends AbstractController
{
    /**
     * @Route("/", name="log_index", methods={"GET"})
     * @Route("/index.php", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        // Check whether user is authenticated
        // TODO: Check if permissions permit user to interact with logs
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get the filters through request query and add to criteria
        $criteria = [];

        $criteria['clientIp'] = $request->query->get('client_ip');
        $criteria['clientPort'] = $request->query->get('client_port');
        $criteria['uri'] = $request->query->get('uri');
        $criteria['method'] = $request->query->get('method');
        $criteria['level'] = $request->query->get('level');
        $criteria['levelName'] = $request->query->get('level_name');
        $criteria['token'] = $request->query->get('token');
        $criteria['message'] = $request->query->get('message');

        /** @var LogRepository $logRepo */
        $logRepo = $this->getDoctrine()->getRepository(Log::class);
        $logs = $logRepo->findLogsBy($criteria);

        return $this->render('logs/index.html.twig', [
            'logs' => $logs,
        ]);
    }

    /**
     * @Route("/{id}", name="log_show", methods={"GET"})
     */
    public function show(Log $log)
    {
        return $this->render('logs/show.html.twig', [
            'log' => $log,
        ]);
    }
}
