<?php

namespace App\Controller\Staff;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\FaqSubject;
use App\Entity\Faqpage;
use App\Form\FaqType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/faq")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("/", name="faq_index", methods={"GET"})
     * @Route("/index.php", methods={"GET"})
     */
    public function index(): Response
    {
        $faqs = $this->getDoctrine()
            ->getRepository(Faq::class)
            ->findAll();

        return $this->render('faq/index.html.twig', [
            'faqs' => $faqs,
        ]);
    }

    /**
     * @Route("/new", name="faq_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $faq = new Faq();
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($faq);

            // Get the subject field
            $subjectsField = $form->get('subject')->getData();
            
            // Add new Subjects to Faq
            foreach($subjectsField as $subjectAdded) {
                
                // Create new FaqSubject row
                $faqSubject = new FaqSubject();
                $faqSubject->setFaq($faq);
                $faqSubject->setSubject($subjectAdded);

                $faq->addFaqSubject($faqSubject);
                $entityManager->persist($faqSubject);
            }

            $entityManager->flush();

            return $this->redirectToRoute('faq_show', [
                'faqId' => $faq->getFaqId(),
            ]);
        }

        return $this->render('faq/new.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/subjects", name="faq_show_subjects", methods={"GET"})
     */
    public function displayFaqsBySubjects(): Response {
        $subject_faqs = $this->getDoctrine()
        ->getRepository(Faq::class)
        ->getAllFaqsBySubject();

        return $this->render('faq/show_subjects.html.twig', [
            "subject_faqs" => $subject_faqs,
        ]);
    }

    /**
     * @Route("/subject/{subjectId}", name="faq_show_subject", methods={"GET"})
     */
    public function displayFaqsBySubject(Subject $subject): Response {
        $subject_faqs = array(); 
        
        $subject_faqs[$subject->getSubject()] = $this->getDoctrine()
        ->getRepository(Faq::class)
        ->getFaqsBySubject($subject);

        return $this->render('faq/show_subjects.html.twig', [
            "subject_faqs" => $subject_faqs,
        ]);
    }

    /**
     * @Route("/collections", name="faq_show_collections", methods={"GET"})
     */
    public function displayFaqsByCollections(): Response {
        $collection_faqs = $this->getDoctrine()
        ->getRepository(Faq::class)
        ->getAllFaqsByCollection();

        return $this->render('faq/show_collections.html.twig', [
            "collection_faqs" => $collection_faqs,
        ]);
    }

    /**
     * @Route("/collection/{faqpageId}", name="faq_show_collection", methods={"GET"})
     */
    public function displayFaqsByCollection(Faqpage $faqPage): Response {
        $collection_faqs = array();
        
        $collection_faqs[$faqPage->getName()] = $this->getDoctrine()
        ->getRepository(Faq::class)
        ->getFaqsByCollection($faqPage);

        return $this->render('faq/show_collections.html.twig', [
            "collection_faqs" => $collection_faqs,
        ]);
    }

    /**
     * @Route("/{faqId}", name="faq_show", methods={"GET"})
     */
    public function show(Faq $faq): Response
    {
        // Get all subjects associated with the faq
        $subjects = $this->getDoctrine()
        ->getRepository(FaqSubject::class)
        ->getSubjectsByFaq($faq);

        return $this->render('faq/show.html.twig', [
            'faq' => $faq,
            'subjects' => $subjects,
        ]);
    }

    /**
     * @Route("/{faqId}/edit", name="faq_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Faq $faq): Response
    {
        // Get all subjects associated with the faq
        $subjects = $this->getDoctrine()
        ->getRepository(FaqSubject::class)
        ->getSubjectsByFaq($faq);

        $form = $this->createForm(FaqType::class, $faq);

        // Set the subject field
        $form->get('subject')->setData($subjects);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Get the subject field, check for any changes
            $subjectsField = $form->get('subject')->getData();
            $diffAdded = array_diff($subjectsField, $subjects); // Newly added subjects
            $diffRemoved = array_diff($subjects, $subjectsField); // Subjects removed
            
            // Add new Subjects to Faq
            foreach($diffAdded as $subjectAdded) {
                // Check if FaqSubject row already exists for new subject
                $duplicate = $this->getDoctrine()
                ->getRepository(FaqSubject::class)
                ->findBy(['faq' => $faq, 'subject' => $subjectAdded]);
                
                if (!$duplicate) {
                    // Create new FaqSubject row
                    $faqSubject = new FaqSubject();
                    $faqSubject->setFaq($faq);
                    $faqSubject->setSubject($subjectAdded);

                    $faq->addFaqSubject($faqSubject);
                    $entityManager->persist($faqSubject);
                }
            }

            // Delete old Subjects from Faq
            foreach($diffRemoved as $subjectRemoved) {
                // Check if FaqSubject row to be removed exists
                $exists = $this->getDoctrine()
                ->getRepository(FaqSubject::class)
                ->findOneBy(['faq' => $faq, 'subject' => $subjectRemoved]);
                
                //return new Response($exists->getFaqSubjectId());
                if ($exists) {
                    // Delete the associated FaqSubject
                    $faq->removeFaqSubject($exists);
                    $entityManager->remove($exists);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('faq_show', [
                'faqId' => $faq->getFaqId(),
            ]);
        }

        return $this->render('faq/edit.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{faqId}", name="faq_delete", methods={"POST"})
     */
    public function delete(Request $request, Faq $faq): Response
    {
        if ($this->isCsrfTokenValid('delete'.$faq->getFaqId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            // Delete FaqSubject's associated
            $faqSubjects = $this->getDoctrine()
                ->getRepository(FaqSubject::class)
                ->findBy(['faq' => $faq]);

            foreach($faqSubjects as $faqSubject) {
                $entityManager->remove($faqSubject);
            }
            
            $entityManager->remove($faq);
            $entityManager->flush();
        }

        return $this->redirectToRoute('faq_index');
    }
}
