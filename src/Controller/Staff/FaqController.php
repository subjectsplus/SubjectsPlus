<?php

namespace App\Controller\Staff;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\FaqSubject;
use App\Entity\Faqpage;
use App\Entity\FaqFaqpage;
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

            // Get the subject field
            $subjectsField = $form->get('subject')->getData();

            // Get the faqpage field
            $faqpagesField = $form->get('faqpage')->getData();
            
            // Add new Subjects to Faq
            foreach($subjectsField as $subjectAdded) {
                
                // Create new FaqSubject row
                $faqSubject = new FaqSubject();
                $faqSubject->setFaq($faq);
                $faqSubject->setSubject($subjectAdded);

                $faq->addFaqSubject($faqSubject);
                $entityManager->persist($faqSubject);
            }

            // Add new Faqpages to Faq
            foreach($faqpagesField as $faqpageAdded) {
    
                // Create new FaqSubject row
                $faqFaqpage = new FaqFaqpage();
                $faqFaqpage->setFaq($faq);
                $faqFaqpage->setFaqpage($faqpageAdded);

                $faq->addFaqFaqpage($faqFaqpage);
                $entityManager->persist($faqFaqpage);
            }

            $entityManager->persist($faq);
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

        // Get all faqpages/collections associated with the faq
        $faqPages = $this->getDoctrine()
        ->getRepository(FaqFaqpage::class)
        ->getFaqPagesByFaq($faq);

        return $this->render('faq/show.html.twig', [
            'faq' => $faq,
            'subjects' => $subjects,
            'faqpages' => $faqPages,
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

        // Get all faqpages/collections associated with the faq
        $faqPages = $this->getDoctrine()
        ->getRepository(FaqFaqpage::class)
        ->getFaqPagesByFaq($faq);

        $form = $this->createForm(FaqType::class, $faq);

        // Set the subject field
        $form->get('subject')->setData($subjects);

        // Set the faqpage field
        $form->get('faqpage')->setData($faqPages);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Get the subject field, check for any changes
            $subjectsField = $form->get('subject')->getData();
            $subjectsAdded = array_diff($subjectsField, $subjects); // Newly added subjects
            $subjectsRemoved = array_diff($subjects, $subjectsField); // Subjects removed
            
            // Get the faqpage field, check for any changes
            $faqpageField = $form->get('faqpage')->getData();
            $faqpagesAdded = array_diff($faqpageField, $faqPages); // Newly added subjects
            $faqpagesRemoved = array_diff($faqPages, $faqpageField); // Subjects removed

            // Add new Subjects to Faq
            foreach($subjectsAdded as $subjectAdded) {
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
            foreach($subjectsRemoved as $subjectRemoved) {
                // Check if FaqSubject row to be removed exists
                $exists = $this->getDoctrine()
                ->getRepository(FaqSubject::class)
                ->findOneBy(['faq' => $faq, 'subject' => $subjectRemoved]);
                
                if ($exists) {
                    // Delete the associated FaqSubject
                    $faq->removeFaqSubject($exists);
                    $entityManager->remove($exists);
                }
            }

            // Add new Faqpages to Faq
            foreach($faqpagesAdded as $faqpageAdded) {
                // Check if FaqFaqpage row already exists for new subject
                $duplicate = $this->getDoctrine()
                ->getRepository(FaqFaqpage::class)
                ->findBy(['faq' => $faq, 'faqpage' => $faqpageAdded]);
                
                if (!$duplicate) {
                    // Create new FaqFaqpage row
                    $faqFaqPage = new FaqFaqpage();
                    $faqFaqPage->setFaq($faq);
                    $faqFaqPage->setFaqpage($faqpageAdded);

                    $faq->addFaqFaqpage($faqFaqPage);
                    $entityManager->persist($faqFaqPage);
                }
            }

            // Delete old Faqpages from Faq
            foreach($faqpagesRemoved as $faqpageRemoved) {
                // Check if FaqFaqpage row to be removed exists
                $exists = $this->getDoctrine()
                ->getRepository(FaqFaqpage::class)
                ->findOneBy(['faq' => $faq, 'faqpage' => $faqpageRemoved]);
                
                if ($exists) {
                    // Delete the associated FaqFaqpage
                    $faq->removeFaqFaqpage($exists);
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

            // Delete FaqFaqpage's associated
            $faqFaqpages = $this->getDoctrine()
            ->getRepository(FaqFaqpage::class)
            ->findBy(['faq' => $faq]);

            foreach($faqFaqpages as $faqFaqpage) {
                $entityManager->remove($faqFaqpage);
            }

            $entityManager->remove($faq);
            $entityManager->flush();
        }

        return $this->redirectToRoute('faq_index');
    }
}
