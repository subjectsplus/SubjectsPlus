<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Service\PlusletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class GuideController extends AbstractController
{
    private $_twig;
    private $plusletService;

    public function __construct(Environment $twig, PlusletService $plusletService)
    {
        $this->_twig = $twig;
        $this->plusletService = $plusletService;
    }

    /**
     * @Route("subjects/{shortform}", name="guidebyShortname", priority=5, requirements={"shortform"="[A-Za-z0-9]+"})
     */
    public function showPublicGuide(string $shortform): Response
    {
        $subjects = $this->getDoctrine()->getRepository(Subject::class)->findBy(['shortform' => $shortform]);
        if (count($subjects)) {
            return new Response($this->_renderPublicGuide(array_pop($subjects)->getSubjectId()));
        } else {
            throw $this->createNotFoundException('The guide does not exist');
        }
    }

    /**
     * @Route("subjects/guide.php", name="guidebyParam", priority=10)
     */
    public function showPublicGuideByQueryParams(): Response
    {
        $request = Request::createFromGlobals();
        if ($request->query->get('subject')) {
            return $this->showPublicGuide($request->query->get('subject'));
        }
        // TODO: actually do the lookup by ID
        return new Response($this->_renderPublicGuide($request->query->get('id')));
    }

    private function _renderPublicGuide(int $id): string
    {
        // TODO: check visibility; make sure public is allowed to see
        return $this->_twig->render(
            'public/guide/guide.html.twig',
            [
            'guide' => $this->getDoctrine()
            ->getRepository(\App\Entity\Subject::class)
            ->find($id)->toPublicArray($this->plusletService),
            ]
        );
    }
}
