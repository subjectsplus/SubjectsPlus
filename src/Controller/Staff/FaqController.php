<?php

namespace App\Controller\Staff;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\FaqSubject;
use App\Entity\Faqpage;
use App\Entity\FaqFaqpage;
use App\Entity\Chchchanges;
use App\Form\FaqType;
use App\Service\FaqService;
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
    public function new(Request $request, FaqService $fs): Response
    {
        // Create a new Faq entry
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
            $fs->addSubjectsToFaq($faq, $subjectsField);

            // Add new Faqpages to Faq
            $fs->addFaqpagesToFaq($faq, $faqpagesField);

            // Persist and save the Faq, FaqSubject's, and FaqFaqpages's to Database
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
        // Get all Faq's by Subject
        /** @var FaqRepository $faqRepo */
        $faqRepo = $this->getDoctrine()->getRepository(Faq::class);

        // FORMAT [string: Subject Name] = array: Faq
        $subject_faqs = $faqRepo->getAllFaqsBySubject();

        return $this->render('faq/show_subjects.html.twig', [
            "subject_faqs" => $subject_faqs,
        ]);
    }

    /**
     * @Route("/subject/{subjectId}", name="faq_show_subject", methods={"GET"})
     */
    public function displayFaqsBySubject(Subject $subject): Response {
        // Display Faq's for a specific Subject
        $subject_faqs = array();

        /** @var FaqRepository $faqRepo */
        $faqRepo = $this->getDoctrine()->getRepository(Faq::class);

        // FORMAT [string: Subject Name] = array: Faq
        $subject_faqs[$subject->getSubject()] = $faqRepo
        ->getFaqsBySubject($subject);

        return $this->render('faq/show_subjects.html.twig', [
            "subject_faqs" => $subject_faqs,
        ]);
    }

    /**
     * @Route("/collections", name="faq_show_collections", methods={"GET"})
     */
    public function displayFaqsByCollections(): Response {
        // Get all Faq's by Collection/Faqpage
        /** @var FaqRepository $faqRepo */
        $faqRepo = $this->getDoctrine()->getRepository(Faq::class);

        // FORMAT [string: Faqpage Name] = array: Faq
        $collection_faqs = $faqRepo->getAllFaqsByCollection();

        return $this->render('faq/show_collections.html.twig', [
            "collection_faqs" => $collection_faqs,
        ]);
    }

    /**
     * @Route("/collection/{faqpageId}", name="faq_show_collection", methods={"GET"})
     */
    public function displayFaqsByCollection(Faqpage $faqPage): Response {
        // Display Faq's for a specific Collection/Faqpage
        $collection_faqs = array();
        
        /** @var FaqRepository $faqRepo */
        $faqRepo = $this->getDoctrine()->getRepository(Faq::class);

        // FORMAT [string: Faqpage Name] = array: Faq
        $collection_faqs[$faqPage->getName()] = $faqRepo
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
        /** @var FaqSubjectRepository $faqSubjectRepo */
        $faqSubjectRepo = $this->getDoctrine()
        ->getRepository(FaqSubject::class);
        $subjects = $faqSubjectRepo->getSubjectsByFaq($faq);

        // Get all faqpages/collections associated with the faq
        /** @var FaqFaqpageRepository $faqFaqpageRepo */
        $faqFaqpageRepo = $this->getDoctrine()
        ->getRepository(FaqFaqpage::class);
        $faqPages = $faqFaqpageRepo->getFaqPagesByFaq($faq);

        // Get all staff associated with the faq
        /** @var ChchchangesRepository $chchchangesRepo */
        $chchchangesRepo = $this->getDoctrine()
        ->getRepository(Chchchanges::class);
        $staff = $chchchangesRepo->getStaffByFaq($faq);

        return $this->render('faq/show.html.twig', [
            'faq' => $faq,
            'subjects' => $subjects,
            'faqpages' => $faqPages,
            'staff' => $staff,
        ]);
    }

    /**
     * @Route("/{faqId}/edit", name="faq_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FaqService $fs, Faq $faq): Response
    {
        // Get all subjects associated with the faq
        /** @var FaqSubjectRepository $faqSubjectRepo */
        $faqSubjectRepo = $this->getDoctrine()->getRepository(FaqSubject::class);
        $subjects = $faqSubjectRepo->getSubjectsByFaq($faq);

        // Get all faqpages/collections associated with the faq
        /** @var FaqFaqpageRepository $faqFaqpageRepo */
        $faqFaqpageRepo = $this->getDoctrine()
        ->getRepository(FaqFaqpage::class);
        $faqPages = $faqFaqpageRepo->getFaqPagesByFaq($faq);

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
            $fs->addSubjectsToFaq($faq, $subjectsAdded);

            // Delete old Subjects from Faq
            $fs->removeSubjectsFromFaq($faq, $subjectsRemoved);

            // Add new Faqpages to Faq
            $fs->addFaqpagesToFaq($faq, $faqpagesAdded);

            // Delete old Faqpages from Faq
            $fs->removeFaqpagesFromFaq($faq, $faqpagesRemoved);

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
    public function delete(Request $request, FaqService $fs, Faq $faq): Response
    {
        // Delete Faq and associated FaqSubject's and FaqFaqPage's
        if ($this->isCsrfTokenValid('delete'.$faq->getFaqId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $fs->deleteFaq($faq);
        }

        return $this->redirectToRoute('faq_index');
    }
}
