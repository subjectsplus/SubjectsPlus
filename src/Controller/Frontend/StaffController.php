<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaffController extends AbstractController
{
    /**
     * @Route("/frontend/staff", name="frontend_staff")
     */
    public function index(): Response
    {
        return $this->render('frontend/staff/index.html.twig', [
            'controller_name' => 'StaffController',
        ]);
    }
}
