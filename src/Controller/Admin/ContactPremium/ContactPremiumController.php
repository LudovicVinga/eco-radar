<?php

namespace App\Controller\Admin\ContactPremium;

use App\Entity\ContactPremium;
use App\Repository\ContactPremiumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class ContactPremiumController extends AbstractController
{
    #[Route('/admin/contact-premium/index', name: 'app_admin_contact_premium_index', methods: ['GET'])]
    public function index(ContactPremiumRepository $contactPremiumRepository): Response
    {
        $contactsPremium = $contactPremiumRepository->findAll();

        return $this->render('pages/admin/contact_premium/index.html.twig', [
            'contactsPremium' => $contactsPremium,
        ]);
    }

    #[Route('/admin/contact-premium/delete/{id<\d+>}', name: 'app_admin_contact_premium_delete', methods: ['POST'])]
    public function delete(Request $request, ContactPremium $contactPremium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid("delete-contactPremium-{$contactPremium->getId()}", $request->request->get('csrf_token'))) {
            $contactPremiumName = "{$contactPremium->getFirstName()} {$contactPremium->getLastName()}";

            $entityManager->remove($contactPremium);
            $entityManager->flush();

            $this->addFlash('success', "Le contact [{$contactPremiumName}] a bien été supprimé.");
        }

        return $this->redirectToRoute('app_admin_contact_premium_index');
    }
}
