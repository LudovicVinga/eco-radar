<?php

namespace App\Controller;

use App\Repository\LabelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('homepage.html.twig');
    }

    #[Route('/explorer', name: 'explorer')]
    public function explorer(Request $request, LabelRepository $labelRepository): Response
    {
        $pays = $request->query->get('pays');
        $secteur = $request->query->get('secteur');
        $nomLabel = $request->query->get('label');

        $labels = $labelRepository->findByFilters($pays, $secteur, $nomLabel);

        return $this->render('explorer.html.twig', [
            'labels' => $labels,
            'pays' => $pays,
            'secteur' => $secteur,
            'label' => $nomLabel,
        ]);
    }

    #[Route('/visualisations', name: 'visualisations')]
public function visualisations(LabelRepository $labelRepository): Response
{
    $countryStats = $labelRepository->countByCountry();
    $sectorStats = $labelRepository->countBySector();
    $certStats = $labelRepository->countByCertification();

    // Formatage des données pour Chart.js
    $countryLabels = array_column($countryStats, 'country');
    $countryData = array_column($countryStats, 'count');

    $sectorLabels = array_column($sectorStats, 'sector');
    $sectorData = array_column($sectorStats, 'count');

    $certLabels = ['Non certifiés', 'Certifiés'];
    $certData = [0, 0];

    foreach ($certStats as $row) {
        if ($row['certified']) {
            $certData[1] = $row['count'];
        } else {
            $certData[0] = $row['count'];
        }
    }

    return $this->render('visualisations.html.twig', [
        'countryLabels' => json_encode($countryLabels),
        'countryData' => json_encode($countryData),
        'sectorLabels' => json_encode($sectorLabels),
        'sectorData' => json_encode($sectorData),
        'certLabels' => json_encode($certLabels),
        'certData' => json_encode($certData),
    ]);
}


    #[Route('/a-propos', name: 'a_propos')]
    public function aPropos(): Response
    {
        return $this->render('a_propos.html.twig');
    }
}
