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

        // Get the filters through request query
        $clientIp = $request->query->get('client_ip');
        $clientPort = $request->query->get('client_port');
        $uri = $request->query->get('uri');
        $method = $request->query->get('method');
        $level = $request->query->get('level');
        $levelName = $request->query->get('level_name');
        $token = $request->query->get('token');

        /** @var LogRepository $logRepo */
        $logRepo = $this->getDoctrine()->getRepository(Log::class);
        $logs = $logRepo->findLogsBy($level, $levelName, $clientIp, $clientPort, $uri, $method, $token);

        return $this->render('logs/index.html.twig', [
            'logs' => $logs,
        ]);
    }
}
