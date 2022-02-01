<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Subject;
use App\Service\LinkCheckService;
use Symfony\Component\HttpFoundation\JsonResponse;

class LinkCheckerController extends AbstractController
{

    private $linkCheckService;

    public function __construct(LinkCheckService $linkCheckService)
    {
        $this->linkCheckService = $linkCheckService;
    }

    /**
     * @Route("/control/guides/link_checker.php", name="staff_link_checker")
     */
    public function index(): Response
    {
        return $this->render('backend/link_checker/link_checker.html.twig', [
            'guides' => $this->getDoctrine()
                             ->getRepository(Subject::class)
                             ->getGuideListForStaff()
        ]);
    }

    /**
     * @Route("/control/guides/check_links.json", name="link_checker_api")
     */
    public function links(): JsonResponse
    {
        $urls = [
            'https://library.linnbenton.edu',
            'https://link.gale.com/apps/portal/DDFVPU123331068/UHIC?u=lbcc&sid=UHIC&xid=0d79379c',
            'https://www.loc.gov/search/?q=World%20War%20I',
            'https://ebookcentral.proquest.com/lib/linnbenton-ebooks/search.action?query=World+War%2C+1914-1918',
            'https://guides.library.harvard.edu/HistSciInfo/secondary',
            'https://www.linnbenton.edu/student-services/library-tutoring-testing/library/getting-materials/index.php',
            'http://libcat.linnbenton.edu/eg/opac/results?query=World+War+1914+1918&qtype=subject&fi%3Asearch_format=dvd&locg=8&detail_record_view=1&sort=',
            'https://video-alexanderstreet-com.ezproxy.libweb.linnbenton.edu/channel/world-war-i-1914-1918?source=suggestion',
            'http://fod.infobase.com/p_Search.aspx?bc=0&rd=a&q=world%20war%20i',
            'https://libcat.linnbenton.edu/eg/opac/results?detail_record_view=1&query=World%20War%20(1914-1918)',
            'https://ebookcentral.proquest.com/lib/linnbenton-ebooks/search.action?query=World+War%2C+1914-1918',
            'https://copyright.cornell.edu/publicdomain',
            'http://guides.library.jhu.edu/primary-sources-history',
            'https://www.linnbenton.edu/student-services/library-tutoring-testing/library/index2php'
        ];
        return new JsonResponse($this->linkCheckService->getUrlStatuses($urls));

    }
}

