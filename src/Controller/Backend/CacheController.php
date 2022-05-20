<?php

namespace App\Controller\Backend;

use App\Service\CacheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control/cache")
 */
class CacheController extends AbstractController
{
    /**
     * @var CacheService
     */
    private $_cs;

    /**
     * @param CacheService $cacheService
     */
    public function __construct(CacheService $cacheService)
    {
        $this->_cs = $cacheService;
    }
    /**
     * @Route("/", name="control_cache")
     */
    public function index(): Response
    {
        return $this->render('backend/cache/index.html.twig', [
            'controller_name' => 'CacheController',
            'apcu_enabled' => $this->_cs->isApcuEnabled(),
            'apcu_info' => $this->_cs->getApcuCacheInfo(),
        ]);
    }
}
