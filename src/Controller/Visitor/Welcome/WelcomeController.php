<?php

namespace App\Controller\Visitor\Welcome;

use App\Repository\LabelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_visitor_welcome', methods: ['GET'])]
    public function index(LabelRepository $labelRepository): Response
    {
        $labels = $labelRepository->findBy(['isPublished' => true], ['publishedAt' => 'DESC'], 3);

        return $this->render('pages/visitor/welcome/index.html.twig', [
            'labels' => $labels,
        ]);
    }
}
