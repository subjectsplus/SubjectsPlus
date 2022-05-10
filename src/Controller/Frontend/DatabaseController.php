<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DatabaseController extends AbstractController
{
    /**
     * @Route("/frontend/database", name="frontend_database")
     */
    public function index(): Response
    {
        return $this->render('frontend/database/index.html.twig', [
            'controller_name' => 'DatabaseController',
        ]);
    }
}
