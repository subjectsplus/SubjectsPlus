<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkCheckerController extends AbstractController
{
    /**
     * @Route("/backend/link/checker", name="backend_link_checker")
     */
    public function index(): Response
    {
        return $this->render('backend/link_checker/index.html.twig', [
            'controller_name' => 'LinkCheckerController',
        ]);
    }
}
