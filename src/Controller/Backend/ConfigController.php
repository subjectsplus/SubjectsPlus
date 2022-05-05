<?php

namespace App\Controller\Backend;

use App\Entity\Config;
use App\Form\ConfigType;
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
     * @Route("/", name="app_backend_config")
     */
    public function index(ConfigRepository $configRepository): Response
    {

        $configs = $configRepository->findAll();
        return $this->render('backend/config/index.html.twig', [
            'controller_name' => 'ConfigController',
            'configs' => $configs,
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

                return $this->redirectToRoute('app_backend_config');
            }

            return $this->renderForm('backend/config/new.html.twig', [
                'form' => $form,
            ]);
        }
}
