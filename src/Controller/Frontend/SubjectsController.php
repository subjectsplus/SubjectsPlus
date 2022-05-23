<?php

namespace App\Controller\Frontend;

use App\Entity\Subject;
use App\Logger\RequestProcessor;
use App\Service\SubjectService;
use App\Service\ThemeService;
use App\Controller\Frontend\FrontendBaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class SubjectsController extends FrontendBaseController
{
    private $subjectService;

    public function __construct(ThemeService $themeService, SubjectService $subjectService)
    {
        parent::__construct($themeService);
        $this->subjectService = $subjectService;
    }

    /**
     * @Route("/", name="frontend_subjects")
     */
    public function index(): Response
    {
        $subjects = $this->subjectService->getSubjectIndex();

        return $this->render('subjects/index.html.twig', [
            'controller_name' => 'SubjectsController',
            'subjects' => $subjects
        ]);
    }

    /**
     * @Route("/{shortform}", name="subject_show", methods={"GET"}, requirements={"shortform"="^(?!\b(api|control)\b(?![\w-]))[a-z0-9-]+$"})
     */
    public function show(Request $request, Subject $subject): Response
    {
        return $this->render('subjects/show.html.twig', [
            'subject' => $subject
        ]);
    }
}
