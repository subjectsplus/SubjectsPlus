<?php

namespace App\Controller\Backend;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\Faqpage;
use App\Entity\Chchchanges;
use App\Entity\Staff;
use App\Entity\Media;
use App\Form\FaqType;
use App\Service\FaqService;
use App\Service\ChangeLogService;
use App\Service\MediaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Log\LoggerInterface;

/**
 * @Route("/control/faq")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("/", name="faq_index", methods={"GET"})
     * @Route("/index.php", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $activeFlag = $request->query->get('active');
        
        if ($activeFlag !== null && ($activeFlag == 0 || $activeFlag == 1)) {
            $faqs = $this->getDoctrine()
                ->getRepository(Faq::class)
                ->findBy(['active' => $activeFlag]);
        } else {
            $faqs = $this->getDoctrine()
                ->getRepository(Faq::class)
                ->findAll();
        }

        return $this->render('backend/faq/index.html.twig', [
            'faqs' => $faqs,
        ]);
    }

    /**
     * @Route("/new", name="faq_new", methods={"GET","POST"})
     */
    public function new(Request $request, FaqService $fs, ChangeLogService $cls, LoggerInterface $logger, MediaService $ms): Response
    {
        // Check whether user is authenticated
        // TODO: Check if permissions permit user to create new faq
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Create a new Faq entry
        $faq = new Faq();
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        // Get all media associated with the staff member
        /** @var Staff $staff */
        $staff = $this->getUser();
        /** @var MediaRepository $mediaRepo */
        $mediaRepo = $this->getDoctrine()->getRepository(Media::class);
        $staffMedia = $mediaRepo->findByStaff($staff);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->transactional(function() use($form, $faq, $entityManager, $fs, $cls, $ms, $logger) {
                // Persist Faq entity
                $entityManager->persist($faq);
                $entityManager->flush();

                // Get the keywords field
                $keywordsField = $form->get('keywords')->getData();

                // Get the subject field
                $subjectsField = $form->get('subject')->getData();
                
                // Get the faqpage field
                $faqpagesField = $form->get('faqpage')->getData();
                
                // Add new Keywords to Faq
                if (!empty(trim($keywordsField))) {
                    $keywordsArray = array_map('trim', explode(',', $keywordsField));
                    foreach ($keywordsArray as $keyword) {
                        $faq->addKeyword($keyword);
                    }
                }

                // Add new Subjects to Faq
                if (!empty($subjectsField)) 
                    $fs->addSubjectsToFaq($faq, $subjectsField);
    
                // Add new Faqpages to Faq
                if (!empty($faqpagesField))
                    $fs->addFaqpagesToFaq($faq, $faqpagesField);
                
                // Check for any new images/links added
                $faqId = $faq->getFaqId();
                $questionHtml = $form->get('question')->getData();
                $answerHtml = $form->get('answer')->getData();

                $ms->createAttachmentFromHTML($questionHtml, 'faq', $faqId);
                $ms->createAttachmentFromHTML($answerHtml, 'faq', $faqId);

                // Create new log entry 
                /** @var Staff $staff */
                $staff = $this->getUser();
                $question = $faq->getQuestion();
                $cls->addLog($staff, 'faq', $faqId, $question, 'insert');

                // Create flash message
                $this->addFlash('notice', 'Success! Created new Faq!');
            });

            return $this->redirectToRoute('faq_show', [
                'faqId' => $faq->getFaqId(),
            ]);
        }

        return $this->render('backend/faq/new.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
            'media' => $staffMedia,
        ]);
    }

    /**
     * @Route("/subjects", name="faq_show_subjects", methods={"GET"})
     */
    public function displayFaqsBySubjects(Request $request): Response {
        return $this->render('backend/faq/show_subjects.html.twig', [
            "subjects" => $this->getDoctrine()->getRepository(Subject::class)->findAll(),
        ]);
    }

    /**
     * @Route("/subject/{subjectId}", name="faq_show_subject", methods={"GET"})
     */
    public function displayFaqsBySubject(Request $request, Subject $subject): Response {
        return $this->render('backend/faq/show_subjects.html.twig', [
            "subjects" => [
                $subject
            ]
        ]);
    }

    /**
     * @Route("/collections", name="faq_show_collections", methods={"GET"})
     */
    public function displayFaqsByCollections(Request $request): Response {
        return $this->render('backend/faq/show_collections.html.twig', [
            "collections" => $this->getDoctrine()->getRepository(Faqpage::class)->findAll(),
        ]);
    }

    /**
     * @Route("/collection/{faqpageId}", name="faq_show_collection", methods={"GET"})
     */
    public function displayFaqsByCollection(Request $request, Faqpage $faqPage): Response {
        return $this->render('backend/faq/show_collections.html.twig', [
            "collection_faqs" => [
                $faqPage
            ]
        ]);
    }

    /**
     * @Route("/staffmember/{staffId}", name="faq_show_by_staff_member", methods={"GET"})
     */
    public function displayFaqsByStaffMember(Request $request, Staff $staff): Response {
        // Get all faqs associated with the staff member
        /** @var ChchchangesRepository $chchchangesRepo */
        $chchchangesRepo = $this->getDoctrine()
        ->getRepository(Chchchanges::class);

        $activeFlag = $request->query->get('active');

        $faqs = $chchchangesRepo->getFaqsByStaff($staff, $activeFlag);

        $staffName = trim($staff->getFName() . ' ' . $staff->getLName());
        $staffEmail = trim($staff->getEmail());

        return $this->render('backend/faq/show_staffmember.html.twig', [
            "faqs" => $faqs,
            "staffName" => $staffName,
            "staffEmail" => $staffEmail,
        ]);
    }

    /**
     * @Route("/{faqId}", name="faq_show", methods={"GET"})
     */
    public function show(Faq $faq): Response
    {
        // Get all staff associated with the faq
        /** @var ChchchangesRepository $chchchangesRepo */
        $chchchangesRepo = $this->getDoctrine()
        ->getRepository(Chchchanges::class);
        $update_history = $chchchangesRepo->getStaffByFaq($faq);

        return $this->render('backend/faq/show.html.twig', [
            'faq' => $faq,
            'updateHistory' => $update_history,
        ]);
    }

    /**
     * @Route("/{faqId}/edit", name="faq_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FaqService $fs, ChangeLogService $cls, MediaService $ms, Faq $faq): Response
    {
        // Check whether user is authenticated
        // TODO: Check if permissions permit user to edit the faq
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get all media associated with the staff member
        /** @var Staff $staff */
        $staff = $this->getUser();
        /** @var MediaRepository $mediaRepo */
        $mediaRepo = $this->getDoctrine()->getRepository(Media::class);
        $staffMedia = $mediaRepo->findByStaff($staff);
        /** @var \App\Repository\TitleRepository $titleRepo */
        $titleRepo = $this->getDoctrine()->getRepository(\App\Entity\Title::class);
        $records = $titleRepo->findBy([], ['title' => 'ASC'], 5, 0);

        // Get all keywords associated with the faq
        $keywords = $faq->getKeywords();
        $keywordsString = '';
        if ($keywords !== null) {
            $keywordsString = implode(',', $keywords);
        } else {
            $keywords = [];
        }

        // Get all subjects associated with the faq
        $subjects = $faq->getSubjects()->toArray();

        // Get all faqpages/collections associated with the faq
        $faqPages = $faq->getFaqpages()->toArray();

        $form = $this->createForm(FaqType::class, $faq);

        // Set the keywords field
        $form->get('keywords')->setData($keywordsString);

        // Set the subject field
        $form->get('subject')->setData($subjects);

        // Set the faqpage field
        $form->get('faqpage')->setData($faqPages);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->transactional(function() use ($faq, $fs, $cls, $ms, $form, $keywords, $subjects, $faqPages) {
                // Get the keywords field, check for any changes
                $keywordsField = $form->get('keywords')->getData();
                $keywordsFieldArray = empty(trim($keywordsField)) ? [] : array_map('trim', explode(',', $keywordsField));
                $keywordsAdded = array_diff($keywordsFieldArray, $keywords); // keywords added
                $keywordsRemoved = array_diff($keywords, $keywordsFieldArray); // keywords removed

                // Get the subject field, check for any changes
                $subjectsField = $form->get('subject')->getData();
                $subjectsAdded = array_diff($subjectsField, $subjects); // Newly added subjects
                $subjectsRemoved = array_diff($subjects, $subjectsField); // Subjects removed
                
                // Get the faqpage field, check for any changes
                $faqpageField = $form->get('faqpage')->getData();
                $faqpagesAdded = array_diff($faqpageField, $faqPages); // Newly added subjects
                $faqpagesRemoved = array_diff($faqPages, $faqpageField); // Subjects removed
            
                // Add new keywords to Faq
                foreach ($keywordsAdded as $keywordAdded) {
                    $faq->addKeyword($keywordAdded);
                }

                // Remove old keywords from Faq
                foreach ($keywordsRemoved as $keywordRemoved) {
                    $faq->removeKeyword($keywordRemoved);
                }

                // Add new Subjects to Faq
                if (!empty($subjectsAdded))
                    $fs->addSubjectsToFaq($faq, $subjectsAdded);

                // Delete old Subjects from Faq
                if (!empty($subjectsRemoved))
                    $fs->removeSubjectsFromFaq($faq, $subjectsRemoved);

                // Add new Faqpages to Faq
                if (!empty($faqpagesAdded))
                    $fs->addFaqpagesToFaq($faq, $faqpagesAdded);

                // Delete old Faqpages from Faq
                if (!empty($faqpagesRemoved))
                    $fs->removeFaqpagesFromFaq($faq, $faqpagesRemoved);

                // Check for any new images/links added/removed
                $faqId = $faq->getFaqId();
                $questionHtml = $form->get('question')->getData();
                $answerHtml = $form->get('answer')->getData();

                $ms->removeAttachmentFromHTML($questionHtml, 'faq', $faqId);
                $ms->removeAttachmentFromHTML($answerHtml, 'faq', $faqId);
                $ms->createAttachmentFromHTML($questionHtml, 'faq', $faqId);
                $ms->createAttachmentFromHTML($answerHtml, 'faq', $faqId);
                
                // Create new log entry
                /** @var Staff $staff */
                $staff = $this->getUser();
                $faqId = $faq->getFaqId();
                $question = $faq->getQuestion();
                $cls->addLog($staff, 'faq', $faqId, $question, 'update');

                // Create flash message
                $this->addFlash('notice', 'Success! Changes saved to FAQ!');
            });

            return $this->redirectToRoute('faq_show', [
                'faqId' => $faq->getFaqId(),
            ]);
        }

        // Get all staff associated with the faq
        /** @var ChchchangesRepository $chchchangesRepo */
        $chchchangesRepo = $this->getDoctrine()
            ->getRepository(Chchchanges::class);
        $update_history = $chchchangesRepo->getStaffByFaq($faq);

        return $this->render('backend/faq/edit.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
            'media' => $staffMedia,
            'records' => $records,
            'updateHistory' => $update_history,
        ]);
    }

    /**
     * @Route("/{faqId}", name="faq_delete", methods={"POST"})
     */
    public function delete(Request $request, FaqService $fs, ChangeLogService $cls, Faq $faq): Response
    {
        // Check whether user is authenticated
        // TODO: Check if permissions permit user to delete the faq
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Delete Faq and associated FaqSubject's and FaqFaqPage's
        if ($this->isCsrfTokenValid('delete'.$faq->getFaqId(), $request->request->get('_token'))) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->transactional(function() use($faq, $fs, $cls) {
                // Preserve before deletion
                $faqId = $faq->getFaqId();
                $question = $faq->getQuestion();

                // Delete Faq
                $fs->deleteFaq($faq);

                // Create new log entry
                /** @var Staff $staff */
                $staff = $this->getUser();
                $cls->addLog($staff, 'faq', $faqId, $question, 'delete');

                // Create flash message
                $this->addFlash('notice', 'Success! Deleted FAQ!');
            });
        }

        return $this->redirectToRoute('faq_index');
    }
}
