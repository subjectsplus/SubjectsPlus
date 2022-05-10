<?php

namespace App\Controller\Frontend;

use App\Entity\Subject;
use App\Logger\RequestProcessor;
use App\Service\SubjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubjectsController extends AbstractController
{
    private $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }
    /**
     * @Route("/", name="frontend_subjects")
     */
    public function index(): Response
    {
        $subjects = $this->subjectService->getSubjectIndex();


        return $this->render('frontend/subjects/index.html.twig', [
            'controller_name' => 'SubjectsController',
            'subjects' => $subjects
        ]);
    }

    /**
     * @Route("/{shortform}", name="subject_show", methods={"GET"})
     *
     */
    public function show(Request $request, $shortform): Response
    {


        $subject = $this->subjectService->getSubjectByShortForm($shortform);
        return $this->render('frontend/subjects/show.html.twig', [
            'subject' => $subject
        ]);
    }


}
