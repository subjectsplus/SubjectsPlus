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
        $ip = $request->query->get('client_ip');
        $uri = $request->query->get('uri');
        $method = $request->query->get('method');
        $level = $request->query->get('level');
        $levelName = $request->query->get('level_name');

        // Set criteria
        $criteria = [];

        if ($ip !== null) {
            $criteria['clientIp'] = $ip;
        }
        
        if ($uri !== null) {
            $criteria['uri'] = $uri;
        }

        if ($method !== null) {
            $criteria['method'] = $method;
        }

        if ($level !== null) {
            $criteria['level'] = $level;
        }

        if ($levelName !== null) { 
           $criteria['levelName'] = $levelName;
        }

        /** @var LogRepository $logRepo */
        $logRepo = $this->getDoctrine()->getRepository(Log::class);
        $logs = $logRepo->findBy($criteria);

        return $this->render('logs/index.html.twig', [
            'logs' => $logs,
        ]);
    }
}
