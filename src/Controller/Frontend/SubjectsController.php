<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubjectsController extends AbstractController
{
    /**
     * @Route("/", name="frontend_subjects")
     */
    public function index(): Response
    {



        return $this->render('frontend/subjects/index.html.twig', [
            'controller_name' => 'SubjectsController',
        ]);
    }


}
