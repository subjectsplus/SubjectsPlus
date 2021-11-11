<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/backend/department", name="backend_department")
     */
    public function index(): Response
    {
        return $this->render('backend/department/index.html.twig', [
            'controller_name' => 'DepartmentController',
        ]);
    }
}
