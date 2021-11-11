<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DasboardController extends AbstractController
{
    /**
     * @Route("/backend/dasboard", name="backend_dasboard")
     */
    public function index(): Response
    {
        return $this->render('backend/dasboard/index.html.twig', [
            'controller_name' => 'DasboardController',
        ]);
    }
}
