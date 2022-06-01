<?php

namespace App\Controller\Backend;

use App\Entity\Title;
use App\Form\TitleType;
use App\Repository\TitleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/title")
 */
class TitleController extends AbstractController
{
    /**
     * @Route("/", name="backend_title_index", methods={"GET"})
     */
    public function index(TitleRepository $titleRepository): Response
    {
        return $this->render('backend/title/index.html.twig', [
            'titles' => $titleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backend_title_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $title = new Title();
        $form = $this->createForm(TitleType::class, $title);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($title);
            $entityManager->flush();

            return $this->redirectToRoute('backend_title_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/title/new.html.twig', [
            'title' => $title,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{titleId}", name="backend_title_show", methods={"GET"})
     */
    public function show(Title $title): Response
    {
        return $this->render('backend/title/show.html.twig', [
            'title' => $title,
        ]);
    }

    /**
     * @Route("/{titleId}/edit", name="backend_title_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Title $title, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TitleType::class, $title);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('backend_title_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/title/edit.html.twig', [
            'title' => $title,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{titleId}", name="backend_title_delete", methods={"POST"})
     */
    public function delete(Request $request, Title $title, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$title->getTitleId(), $request->request->get('_token'))) {
            $entityManager->remove($title);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_title_index', [], Response::HTTP_SEE_OTHER);
    }
}
