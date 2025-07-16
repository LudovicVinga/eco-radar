<?php

namespace App\Controller\Visitor\SiteMap;

use App\Repository\LabelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SiteMapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_visitor_sitemap_show', methods: ['GET'])]
    public function show(Request $request, LabelRepository $labelRepository): Response
    {
        $hostName = $request->getSchemeAndHttpHost();

        $urls = [];
        $urls[] = [
            'loc' => $this->generateUrl('app_visitor_welcome'),
        ];

        $response = $this->render('sitemap/show.html.twig', [
            'hostName' => $hostName,
            'urls' => $urls,
        ]);

        $response->headers->set('Content-type', 'text/xml');

        return $response;
    }
}
