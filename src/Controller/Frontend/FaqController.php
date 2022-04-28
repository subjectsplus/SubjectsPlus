<?php

namespace App\Controller\Frontend;

use App\Entity\Faq;
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
     * @Route("/{faqId}", name="frontend_faq_show", methods={"GET"})
     */
    public function show(Faq $faq): Response
    {
        // Find tokens in faq answer and provide with updated info
        $tokenService = new TokenService($this->getDoctrine()->getManager());
        $faqAnswer = $tokenService->updateTokens($faq->getAnswer());
        $faq->setAnswer($faqAnswer);

        return $this->render('frontend/faq/show.html.twig', [
            'faq' => $faq,
        ]);
    }
}
