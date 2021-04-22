<?php

namespace App\Controller\Patron;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DatabaseListController extends AbstractController
{
    /**
     * @Route("/public/database/list", name="public_database_list")
     */
    public function index(): Response
    {
        return $this->render('public/database_list/index.html.twig', [
            'controller_name' => 'DatabaseListController',
        ]);
    }
}
