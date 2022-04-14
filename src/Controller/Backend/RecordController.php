<?php

namespace App\Controller\Backend;

use App\Entity\Title;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/records")
 */
class RecordController extends AbstractController
{
    /**
     * @Route("/", name="record_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('backend/record/index.html.twig', [

        ]);
    }
}
