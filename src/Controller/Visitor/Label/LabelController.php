<?php

namespace App\Controller\Visitor\Label;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LabelController extends AbstractController
{
    #[Route('/label', name: 'app_visitor_label_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/label/index.html.twig', [
        ]);
    }
}
