<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaffController extends AbstractController
{
    /**
     * @Route("/backend/staff", name="backend_staff")
     */
    public function index(): Response
    {
        return $this->render('backend/staff/index.html.twig', [
            'controller_name' => 'StaffController',
        ]);
    }
}
