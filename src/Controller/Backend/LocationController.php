<?php

namespace App\Controller\Backend;

use App\Entity\Location;
use App\Form\Location1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/location")
 */
class LocationController extends AbstractController
{
    /**
     * @Route("/", name="backend_location_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $locations = $entityManager
            ->getRepository(Location::class)
            ->findAll();

        return $this->render('backend/location/index.html.twig', [
            'locations' => $locations,
        ]);
    }

    /**
     * @Route("/new", name="backend_location_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = new Location();
        $form = $this->createForm(Location1Type::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('backend_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/location/new.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{locationId}", name="backend_location_show", methods={"GET"})
     */
    public function show(Location $location): Response
    {
        return $this->render('backend/location/show.html.twig', [
            'location' => $location,
        ]);
    }

    /**
     * @Route("/{locationId}/edit", name="backend_location_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Location1Type::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('backend_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/location/edit.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{locationId}", name="backend_location_delete", methods={"POST"})
     */
    public function delete(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getLocationId(), $request->request->get('_token'))) {
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_location_index', [], Response::HTTP_SEE_OTHER);
    }
}
