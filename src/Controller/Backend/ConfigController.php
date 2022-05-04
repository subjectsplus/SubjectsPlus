<?php

namespace App\Controller\Backend;

use App\Entity\Config;
use App\Form\ConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/config", name="app_backend_config")
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/", name="app_backend_config")
     */
    public function index(): Response
    {
        return $this->render('backend/config/index.html.twig', [
            'controller_name' => 'ConfigController',
        ]);
    }

    /**
     * @Route("/new", name="config_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
        {
            $config = new Config();
            // ...

            $form = $this->createForm(ConfigType::class, $config);

            return $this->renderForm('backend/config/new.html.twig', [
                'form' => $form,
            ]);
        }
}
