<?php

namespace App\Controller\Frontend;

use App\Entity\Faq;
use App\Service\ThemeService;
use App\Controller\Frontend\FrontendBaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TokenService;

/**
 * @Route("/faq")
 */
class FaqController extends FrontendBaseController
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
     * @Route("/{faqId}", name="frontend_faq_show", methods={"GET"})
     */
    public function show(Faq $faq): Response
    {
        return $this->render('faq/show.html.twig', [
            'faq' => $faq,
        ]);
    }
}
