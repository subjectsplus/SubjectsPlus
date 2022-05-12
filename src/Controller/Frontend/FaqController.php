<?php

namespace App\Controller\Frontend;

use App\Entity\Faq;
use App\Service\ThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TokenService;

/**
 * @Route("/faq")
 */
class FaqController extends AbstractController
{
    /**
     * @var ThemeService
     */
    private $themeService;

    /**
     * @param ThemeService $themeService
     */
    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }
    /**
     * @Route("/{faqId}", name="frontend_faq_show", methods={"GET"})
     */
    public function show(Faq $faq): Response
    {
        // Find tokens in faq answer and provide with updated info
        $tokenService = new TokenService($this->getDoctrine()->getManager());
        $faqAnswer = $tokenService->updateTokens($faq->getAnswer());
        $faq->setAnswer($faqAnswer);

        return $this->render($this->themeService->getThemePath('frontend/faq/show.html.twig'), [
            'faq' => $faq,
        ]);
    }
}
