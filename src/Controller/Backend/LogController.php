<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    /**
     * @Route("/backend/log", name="backend_log")
     */
    public function index(): Response
    {
        return $this->render('backend/log/index.html.twig', [
            'controller_name' => 'LogController',
        ]);
    }
}
