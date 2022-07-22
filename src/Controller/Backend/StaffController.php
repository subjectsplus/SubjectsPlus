<?php

namespace App\Controller\Backend;


use App\Entity\Staff;
use App\Entity\MediaAttachment;
use App\Service\StaffService;
use App\Form\StaffType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/staff")
 */
class StaffController extends AbstractController
{
    /**
     * @Route("/", name="staff_index", methods={"GET"})
     */
    public function index(): Response
    {
        $staff = $this->getDoctrine()
                      ->getRepository(Staff::class)
                      ->findAll();

        return $this->render('backend/staff/index.html.twig', [
            'staff' => $staff,
        ]);
    }

    /**
     * @Route("/new", name="staff_new", methods={"GET","POST"})
     */
    public function new(Request $request, StaffService $ss): Response
    {
        $staff = new Staff();
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($staff);

            $staffPhotoAttachment = $form->get('staffPhoto')->getData();
            if ($staffPhotoAttachment) {
                $ss->processStaffPhoto($staffPhotoAttachment, $staff);
            }

            $entityManager->flush();

            return $this->redirectToRoute('staff_show', [
                'staffId' => $staff->getStaffId(),
            ]);
        }

        return $this->render('backend/staff/new.html.twig', [
            'staff' => $staff,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{staffId}", name="staff_show", methods={"GET"})
     */
    public function show(Staff $staff): Response
    {
        return $this->render('backend/staff/show.html.twig', [
            'staff' => $staff,
        ]);
    }

    /**
     * @Route("/{staffId}/edit", name="staff_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Staff $staff, StaffService $ss): Response
    {
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $staffPhotoAttachment = $form->get('staffPhoto')->getData();
            if ($staffPhotoAttachment) {
                $ss->processStaffPhoto($staffPhotoAttachment, $staff);
            }

            $entityManager->flush();

            return $this->redirectToRoute('staff_show', [
                'staffId' => $staff->getStaffId(),
            ]);
        }

        return $this->render('backend/staff/edit.html.twig', [
            'staff' => $staff,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{staffId}", name="staff_delete", methods={"POST"})
     */
    public function delete(Request $request, Staff $staff): Response
    {
        if ($this->isCsrfTokenValid('delete'.$staff->getStaffId(), $request->request->get('_token'))) {
            // TODO: Rather than delete, set a delete timestamp
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($staff);
            $entityManager->flush();
        }

        return $this->redirectToRoute('staff_index');
    }
}
