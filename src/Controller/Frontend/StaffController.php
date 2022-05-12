<?php

namespace App\Controller\Frontend;

use App\Service\ThemeService;
use App\Controller\Frontend\FrontendBaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaffController extends FrontendBaseController
{

    /**
     * @var ThemeService
     */
    private $themeService;

    public function __construct(ThemeService $themeService)
    {
        parent::__construct($themeService);
    }

    /**
     * @Route("/people", name="frontend_staff")
     */
    public function index(): Response
    {
        return $this->render('staff/index.html.twig', [
            'controller_name' => 'StaffController',
        ]);
    }
}
