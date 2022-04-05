<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Subject;
use App\Entity\Tab;
use App\Entity\Section;
use App\Form\GuideType;

/**
 * @Route("/control/guides")
 */
class GuideController extends AbstractController
{
    /**
     * @Route("/", name="guide_index", methods={"GET"})
     * @Route("/index.php", methods={"GET"})
     */
    public function index(): Response
    {
        $guides = $this->getDoctrine()
        ->getRepository(Subject::class)
        ->findBy(array('active' => 1), array('subject' => 'ASC'));

        return $this->render('backend/guide/index.html.twig', [
            'guides' => $guides,
        ]);
    }

    /**
     * @Route("/new", name="guide_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        // Create a new Guide entry
        $subject = new Subject();
        $form = $this->createForm(GuideType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->transactional(function() use($form, $subject, $entityManager) {
                // Persist Subject entity
                $entityManager->persist($subject);

                // Create new tab
                $tab = new Tab();
                $tab->setSubject($subject);
                $entityManager->persist($tab);

                // Create new section
                $section = new Section();
                $section->setTab($tab);
                $entityManager->persist($section);

                // Create Staff Subject associations
                /** @var \App\Entity\Staff $staff */
                $staff = $this->getUser();
                $subject->addStaff($staff);

                // Create flash message
                $this->addFlash('notice', 'Success! Created new Guide!');
            });

            return $this->redirectToRoute('guide_build', [
                'subjectId' => $subject->getSubjectId(),
            ]);
        }

        return $this->render('backend/guide/new.html.twig', [
            'guide' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/build/{subjectId}", name="guide_build", methods={"GET"})
     */
    public function build(Subject $subject): Response
    {
        return $this->render('backend/guide/builder.html.twig', [
            'guide' => $subject,
        ]);
    }

    /**
     * @Route("/{subjectId}", name="guide_show", methods={"GET"})
     */
    public function show(Subject $subject): Response
    {
        return $this->render('backend/guide/show.html.twig', [
            'guide' => $subject,
        ]);
    }
}
