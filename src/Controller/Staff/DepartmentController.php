<?php

namespace App\Controller\Staff;

use App\Entity\Department;
use App\Form\DepartmentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/department")
 */
class DepartmentController extends AbstractController
{
    /**
     * @Route("/", name="department_index", methods={"GET"})
     */
    public function index(): Response
    {
        $departments = $this->getDoctrine()
            ->getRepository(Department::class)
            ->findAll();

        return $this->render('department/index.html.twig', [
            'departments' => $departments,
        ]);
    }

    /**
     * @Route("/new", name="department_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/new.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{departmentId}", name="department_show", methods={"GET"})
     */
    public function show(Department $department): Response
    {
        return $this->render('department/show.html.twig', [
            'department' => $department,
        ]);
    }

    /**
     * @Route("/{departmentId}/edit", name="department_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Department $department): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/edit.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{departmentId}", name="department_delete", methods={"POST"})
     */
    public function delete(Request $request, Department $department): Response
    {
        if ($this->isCsrfTokenValid('delete'.$department->getDepartmentId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($department);
            $entityManager->flush();
        }

        return $this->redirectToRoute('department_index');
    }
}
