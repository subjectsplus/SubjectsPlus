<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    /**
     * @Route("/backend/faq", name="backend_faq")
     */
    public function index(): Response
    {
        return $this->render('backend/faq/index.html.twig', [
            'controller_name' => 'FaqController',
        ]);
    }
}
