<?php

namespace App\Controller\Backend;

use App\Entity\Config;
use App\Form\ConfigType;
use App\Repository\ConfigCategoryRepository;
use App\Repository\ConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/config")
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="config_index")
     */
    public function index(Request $request, $id, ConfigRepository $configRepository, ConfigCategoryRepository $configCategoryRepository): Response
    {
        $configCategories =
            $configCategoryRepository->findBy(array(), array('category_key' => 'ASC'));

        $configs = $this->getDoctrine()
                       ->getRepository(Config::class)
                       ->findBy(array('configCategory' => $id), array());

        return $this->render('backend/config/index.html.twig', [
            'controller_name' => 'ConfigController',
            'configs' => $configs,
            'configCategories' => $configCategories,
        ]);
    }

    /**
     * @Route("/new", name="config_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $config = new Config();

        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($config);
            $entityManager->flush();

            return $this->redirectToRoute('config_index');
        }

        return $this->renderForm('backend/config/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="config_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Config $config): Response
    {
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('config_index');
        }

        return $this->render('backend/config/edit.html.twig', [
            'config' => $config,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="config_delete", methods={"POST"})
     */
    public function delete(Request $request, Config $config): Response
    {
        if ($this->isCsrfTokenValid('delete'.$config->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($config);
            $entityManager->flush();
        }

        return $this->redirectToRoute('config_index');
    }
}
