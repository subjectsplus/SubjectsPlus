<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/backend/media", name="backend_media")
     */
    public function index(): Response
    {
        return $this->render('backend/media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }
}
