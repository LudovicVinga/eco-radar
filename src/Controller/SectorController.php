<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SectorController extends AbstractController
{
    #[Route('/secteurs', name: 'app_sectors')]
    public function index(): Response
    {
        return $this->render('pages/sector/index.html.twig');
    }
}
