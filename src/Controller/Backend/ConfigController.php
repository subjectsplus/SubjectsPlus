<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    /**
     * @Route("/backend/config", name="app_backend_config")
     */
    public function index(): Response
    {
        return $this->render('backend/config/index.html.twig', [
            'controller_name' => 'ConfigController',
        ]);
    }
}
