<?php

namespace App\Controller\Backend;

use App\Entity\Title;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/record")
 */
class RecordController extends AbstractController
{
    /**
     * @Route("/", name="record_index", methods={"GET"})
     * @Route("/index.php", methods={"GET"})
     */
    public function index(): Response
    {

        $records = $this->getDoctrine()
                     ->getRepository(Title::class)
                     ->findAll();
        return $this->render('backend/record/index.html.twig', [
            'records' => $records,
        ]);
    }
}
