<?php

namespace App\Controller\Backend;

use App\Entity\Log;
use App\Service\DateTimeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/log")
 */
class LogController extends AbstractController
{
    /**
     * @Route("/", name="log_index", methods={"GET"})
     * @Route("/index.php", methods={"GET"})
     */
    public function index(Request $request, DateTimeService $dts): Response
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

        // Set and verify the date follows the format.
        $format = 'Y-m-d';

        $dateFrom = $request->query->get('date_from');
        $dateFrom = $dateFrom ? $dts->verifyDate($dateFrom, $format) : null;
        if ($dateFrom === false) {
            throw new \InvalidArgumentException("date_from must be in $format format.");
        }

        $dateTo = $request->query->get('date_to');
        $dateTo = $dateTo ? $dts->verifyDate($dateTo, $format) : null;
        if ($dateTo === false) {
            throw new \InvalidArgumentException("date_to must be in $format format.");
        }

        $criteria['date_range'] = [$dateFrom, $dateTo];

        /** @var LogRepository $logRepo */
        $logRepo = $this->getDoctrine()->getRepository(Log::class);
        $logs = $logRepo->findLogsBy($criteria);

        return $this->render('backend/log/index.html.twig', [
            'logs' => $logs,
        ]);
    }

    /**
     * @Route("/{id}", name="log_show", methods={"GET"})
     */
    public function show(Log $log)
    {
        return $this->render('backend/log/show.html.twig', [
            'log' => $log,
        ]);
    }
}
