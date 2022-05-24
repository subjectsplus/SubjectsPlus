<?php

namespace App\Controller\Backend;

use App\Entity\Title;
use App\Service\ConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/record")
 */
class RecordController extends AbstractController
{
    private $_configService;

    /**
     * @param ConfigService $configService
     */
    public function __construct(ConfigService $configService) {
        $this->_configService = $configService;
    }
    /**
     * @Route("/", name="record_index", methods={"GET"})
     * @Route("/index.php", methods={"GET"})
     */
    public function index(): Response
    {

        $records = $this->getDoctrine()
                     ->getRepository(Title::class)
                     ->findAll();
        return $this->render('backend/record/index.html.twig', [
            'records' => $records,
            'ctags' => $this->_configService->getConfigValueByKey('ctags'),
        ]);
    }
}
