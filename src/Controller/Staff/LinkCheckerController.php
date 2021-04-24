<?php

namespace App\Controller\Staff;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Subject;

class LinkCheckerController extends AbstractController
{
    /**
     * @Route("/control/guides/link_checker.php", name="staff_link_checker")
     */
    public function index(): Response
    {
        return $this->render('staff/link_checker.html.twig', [
            'guides' => $this->getDoctrine()
                             ->getRepository(Subject::class)
                             ->getGuideListForStaff()
        ]);
    }
}
