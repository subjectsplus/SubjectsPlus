<?php

namespace App\Controller\Staff;

use App\Entity\Faq;
use App\Entity\Subject;
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
            $entityManager->flush();

            return $this->redirectToRoute('faq_index');
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
        ->getFaqsBySubjects();

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
        ->getFaqsByCollections();

        return $this->render('faq/show_collections.html.twig', [
            "collection_faqs" => $collection_faqs,
        ]);
    }

    /**
     * @Route("/{faqId}", name="faq_show", methods={"GET"})
     */
    public function show(Faq $faq): Response
    {
        return $this->render('faq/show.html.twig', [
            'faq' => $faq,
        ]);
    }

    /**
     * @Route("/{faqId}/edit", name="faq_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Faq $faq): Response
    {
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('faq_index');
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
            $entityManager->remove($faq);
            $entityManager->flush();
        }

        return $this->redirectToRoute('faq_index');
    }
}
