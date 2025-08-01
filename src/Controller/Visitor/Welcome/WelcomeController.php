<?php

namespace App\Controller\Visitor\Welcome;

use App\Entity\Contact;
use App\Form\VisitorContactFormType;
use App\Repository\LabelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_visitor_welcome', methods: ['GET', 'POST'])]
    public function home(LabelRepository $labelRepository, Request $request): Response
    {
        $labels = $labelRepository->findBy(['isPublished' => true], ['publishedAt' => 'DESC'], 3);

        $contact = new Contact();
        $form = $this->createForm(VisitorContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO : traitement (ex : envoi d'email ou sauvegarde en base)
            $this->addFlash('success', 'Merci pour votre message !');
            return $this->redirectToRoute('app_visitor_welcome');
        }

        return $this->render('pages/visitor/welcome/index.html.twig', [
            'labels' => $labels,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/a-propos', name: 'a_propos')]
    public function aPropos(): Response
    {
        return $this->render('pages/visitor/a_propos.html.twig');
    }

    #[Route('/explorer', name: 'explorer')]
    public function explorer(LabelRepository $labelRepository): Response
    {
        $labels = $labelRepository->findBy(['isPublished' => true]);

        return $this->render('pages/visitor/explorer.html.twig', [
            'labels' => $labels,
        ]);
    }

    #[Route('/visualisations', name: 'visualisations')]
    public function visualisations(): Response
    {
        return $this->render('pages/visitor/visualisations.html.twig');
    }

    #[Route('/labels/rejoindre', name: 'landing_labels')]
    public function labelsLanding(): Response
    {
        return $this->render('pages/visitor/labels_landing.html.twig');
    }

    
}
