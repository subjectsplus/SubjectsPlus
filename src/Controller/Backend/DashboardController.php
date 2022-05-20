<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
         * @Route("/control/dashboard", name="control_dashboard")
     */
    public function index(): Response
    {
        return $this->render('backend/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
