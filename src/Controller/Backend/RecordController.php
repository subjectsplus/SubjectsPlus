<?php

namespace App\Controller\Backend;

use App\Entity\Record;
use App\Form\RecordType;
use App\Repository\RecordRepository;
use App\Service\RecordService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/record")
 */
class RecordController extends AbstractController
{

    private $_rcdSvc;

    public function __construct(RecordService $recordService)
    {
        $this->_rcdSvc = $recordService;
    }

    /**
     * @Route("/", name="backend_record_index", methods={"GET"})
     */
    public function index(RecordRepository $recordRepository): Response
    {
        return $this->render('backend/record/index.html.twig', [
            'records' => $recordRepository->findAll(),
            'letters' => $this->_rcdSvc->getLetters(),
            'dbs' => $this->_rcdSvc->getDbsByEresDisplay("Y"),
        ]);
    }

    /**
     * @Route("/new", name="backend_record_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $record = new Record();
        $form = $this->createForm(RecordType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($record);
            $entityManager->flush();

            return $this->redirectToRoute('backend_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/record/new.html.twig', [
            'record' => $record,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backend_record_show", methods={"GET"})
     */
    public function show(Record $record): Response
    {
        return $this->render('backend/record/show.html.twig', [
            'record' => $record,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_record_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Record $record, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecordType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('backend_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/record/edit.html.twig', [
            'record' => $record,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backend_record_delete", methods={"POST"})
     */
    public function delete(Request $request, Record $record, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$record->getId(), $request->request->get('_token'))) {
            $entityManager->remove($record);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_record_index', [], Response::HTTP_SEE_OTHER);
    }
}
