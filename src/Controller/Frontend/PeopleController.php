<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PeopleController extends AbstractController
{
    /**
     * @Route("/frontend/people", name="frontend_people")
     */
    public function index(): Response
    {
        return $this->render('frontend/people/index.html.twig', [
            'controller_name' => 'PeopleController',
        ]);
    }
}
