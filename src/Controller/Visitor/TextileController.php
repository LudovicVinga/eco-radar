<?php

namespace App\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/visualisations/textile', name: 'app_textile_')]
class TextileController extends AbstractController
{
    #[Route('/gots', name: 'gots')]
    public function gots(): Response
    {
        return $this->render('pages/visualisations/textile/gots.html.twig');
    }

    #[Route('/oekotex', name: 'oekotex')]
    public function oekotex(): Response
    {
        return $this->render('pages/visualisations/textile/oekotex.html.twig');
    }

    #[Route('/fair-wear', name: 'fairwear')]
    public function fairwear(): Response
    {
        return $this->render('pages/visualisations/textile/fairwear.html.twig');
    }

    #[Route('/autres', name: 'autres')]
    public function autres(): Response
    {
        return $this->render('pages/visualisations/textile/autres.html.twig');
    }
}
